<?php

namespace App\Services;

use App\Models\Package;
use App\Models\Hall;
use App\Models\Booking;
use App\Models\User;
use App\Models\ManagerNotification;
use App\Models\SystemIntegrationLog;
use App\Models\SyncQueue;
use App\Models\BookingStatusHistory;
use App\Events\BookingStatusChanged;
use App\Events\PackageUpdated;
use App\Events\ManagerNotificationCreated;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingUpdateNotification;
use App\Mail\ManagerApprovalRequired;
use Carbon\Carbon;

class DeepIntegrationService
{
    /**
     * Handle package creation and trigger necessary integrations
     */
    public function handlePackageCreated(Package $package)
    {
        try {
            // Update booking system cache
            $this->refreshBookingSystemCache();

            // Notify managers about new package
            $this->notifyManagersOfNewPackage($package);

            // Queue real-time synchronization
            $this->queueSync('package_created', [
                'package_id' => $package->id,
                'package_data' => $package->toArray()
            ], 'high');

            // Log integration success
            $this->logIntegration('package_created', 'admin_dashboard', 'booking_system', [
                'package_id' => $package->id,
                'package_name' => $package->name
            ], 'success');

        } catch (\Exception $e) {
            Log::error('Package creation integration failed', [
                'package_id' => $package->id,
                'error' => $e->getMessage()
            ]);

            $this->logIntegration('package_created', 'admin_dashboard', 'booking_system', [
                'package_id' => $package->id,
                'error' => $e->getMessage()
            ], 'failed', $e->getMessage());
        }
    }

    /**
     * Handle package updates and their impact on active bookings
     */
    public function handlePackageUpdated(Package $package, array $originalData)
    {
        try {
            $startTime = microtime(true);

            // Analyze changes
            $changes = $this->analyzePackageChanges($package, $originalData);

            // Update affected bookings
            if (!empty($changes)) {
                $this->updateAffectedBookings($package, $changes);
            }

            // Refresh booking system cache
            $this->refreshBookingSystemCache();

            // Notify relevant stakeholders
            $this->notifyStakeholdersOfPackageUpdate($package, $changes);

            // Queue real-time synchronization
            $this->queueSync('package_updated', [
                'package_id' => $package->id,
                'changes' => $changes,
                'package_data' => $package->toArray()
            ], 'high');

            $processingTime = (microtime(true) - $startTime) * 1000;

            // Log integration success
            $this->logIntegration('package_updated', 'admin_dashboard', 'booking_system', [
                'package_id' => $package->id,
                'changes' => $changes
            ], 'success', null, $processingTime);

        } catch (\Exception $e) {
            Log::error('Package update integration failed', [
                'package_id' => $package->id,
                'error' => $e->getMessage()
            ]);

            $this->logIntegration('package_updated', 'admin_dashboard', 'booking_system', [
                'package_id' => $package->id,
                'error' => $e->getMessage()
            ], 'failed', $e->getMessage());
        }
    }

    /**
     * Handle package deletion
     */
    public function handlePackageDeleted(array $packageData)
    {
        try {
            // Remove from booking system cache
            $this->removePackageFromCache($packageData['id']);

            // Queue real-time synchronization
            $this->queueSync('package_deleted', [
                'package_id' => $packageData['id'],
                'package_name' => $packageData['name']
            ], 'medium');

            // Log integration success
            $this->logIntegration('package_deleted', 'admin_dashboard', 'booking_system', [
                'package_id' => $packageData['id'],
                'package_name' => $packageData['name']
            ], 'success');

        } catch (\Exception $e) {
            Log::error('Package deletion integration failed', [
                'package_id' => $packageData['id'],
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Handle booking progression through steps
     */
    public function handleBookingProgression(Booking $booking, int $fromStep, int $toStep)
    {
        try {
            $startTime = microtime(true);

            // Update step completion status
            $stepStatus = $booking->step_completion_status ?? [];
            $stepStatus["step_{$toStep}"] = [
                'completed_at' => now(),
                'completed_by' => auth()->id(),
                'data_snapshot' => $this->getBookingDataSnapshot($booking)
            ];
            $booking->update(['step_completion_status' => $stepStatus]);

            // Log progression
            $this->logBookingStatusChange($booking, "step_{$fromStep}", "step_{$toStep}", 'Step progression');

            // Handle step-specific logic
            switch ($toStep) {
                case 4: // Visit scheduling
                    $this->handleVisitSchedulingStep($booking);
                    break;
                case 5: // Wedding details (requires manager approval)
                    $this->handleWeddingDetailsStep($booking);
                    break;
                case 6: // Final summary
                    $this->handleFinalSummaryStep($booking);
                    break;
            }

            $processingTime = (microtime(true) - $startTime) * 1000;

            // Queue real-time synchronization
            $this->queueSync('booking_progression', [
                'booking_id' => $booking->id,
                'from_step' => $fromStep,
                'to_step' => $toStep,
                'step_status' => $stepStatus
            ], 'high');

            // Log integration success
            $this->logIntegration('booking_progression', 'booking_system', 'manager_dashboard', [
                'booking_id' => $booking->id,
                'step_progression' => "{$fromStep} -> {$toStep}"
            ], 'success', null, $processingTime);

        } catch (\Exception $e) {
            Log::error('Booking progression integration failed', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Handle manager visit approval
     */
    public function handleManagerVisitApproval(Booking $booking, array $approvalData)
    {
        try {
            DB::beginTransaction();

            // Update booking with approval data
            $booking->update([
                'visit_confirmed' => true,
                'visit_confirmed_at' => now(),
                'visit_confirmation_method' => $approvalData['method'] ?? 'manual',
                'visit_confirmation_notes' => $approvalData['notes'] ?? null,
                'manager_approval_status' => 'approved',
                'manager_approved_by' => auth()->id(),
                'manager_approved_at' => now(),
                'manager_approval_notes' => $approvalData['manager_notes'] ?? null
            ]);

            // Calculate advance payment amount (20% of estimated total)
            $estimatedTotal = $this->calculateEstimatedBookingTotal($booking);
            $advanceAmount = round($estimatedTotal * 0.20, 2);
            
            $booking->update([
                'estimated_total_cost' => $estimatedTotal,
                'advance_payment_amount' => $advanceAmount,
                'advance_payment_required' => true
            ]);

            // Log status change
            $this->logBookingStatusChange(
                $booking, 
                'visit_pending', 
                'visit_approved', 
                'Manager approved visit request',
                $approvalData
            );

            // Create manager notification for payment tracking
            $this->createManagerNotification(
                auth()->id(),
                'payment_pending',
                'Payment Pending for Approved Visit',
                "Customer {$booking->contact_name} needs to pay advance amount of Rs. " . number_format($advanceAmount),
                [
                    'booking_id' => $booking->id,
                    'advance_amount' => $advanceAmount,
                    'customer_name' => $booking->contact_name,
                    'customer_phone' => $booking->contact_phone
                ],
                'medium',
                true,
                route('manager.bookings.show', $booking->id)
            );

            // Send notification to customer
            $this->sendCustomerVisitApprovalNotification($booking);

            // Queue real-time synchronization
            $this->queueSync('visit_approved', [
                'booking_id' => $booking->id,
                'advance_amount' => $advanceAmount,
                'estimated_total' => $estimatedTotal
            ], 'critical');

            DB::commit();

            // Log integration success
            $this->logIntegration('visit_approval', 'manager_dashboard', 'booking_system', [
                'booking_id' => $booking->id,
                'advance_amount' => $advanceAmount
            ], 'success');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Manager visit approval integration failed', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }

    /**
     * Handle payment confirmation by manager
     */
    public function handlePaymentConfirmation(Booking $booking, array $paymentData)
    {
        try {
            DB::beginTransaction();

            // Update booking with payment confirmation
            $booking->update([
                'advance_payment_paid' => true,
                'advance_payment_paid_at' => now(),
                'payment_confirmation_status' => 'confirmed',
                'payment_confirmed_by' => auth()->id(),
                'payment_confirmed_at' => now(),
                'payment_method' => $paymentData['payment_method'] ?? 'cash',
                'payment_transaction_id' => $paymentData['transaction_id'] ?? null,
                'payment_notes' => $paymentData['notes'] ?? null
            ]);

            // Unlock Step 5 for customer
            $stepStatus = $booking->step_completion_status ?? [];
            $stepStatus['step_5_unlocked'] = [
                'unlocked_at' => now(),
                'unlocked_by' => auth()->id(),
                'payment_confirmed' => true
            ];
            $booking->update(['step_completion_status' => $stepStatus]);

            // Log status change
            $this->logBookingStatusChange(
                $booking, 
                'payment_pending', 
                'payment_confirmed', 
                'Manager confirmed advance payment',
                $paymentData
            );

            // Send notification to customer about Step 5 unlock
            $this->sendCustomerStep5UnlockNotification($booking);

            // Update package and hall statistics
            $this->updateBookingStatistics($booking);

            // Queue real-time synchronization
            $this->queueSync('payment_confirmed', [
                'booking_id' => $booking->id,
                'step_5_unlocked' => true
            ], 'critical');

            DB::commit();

            // Log integration success
            $this->logIntegration('payment_confirmation', 'manager_dashboard', 'booking_system', [
                'booking_id' => $booking->id,
                'payment_amount' => $booking->advance_payment_amount
            ], 'success');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment confirmation integration failed', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }

    /**
     * Notify active bookings of package updates
     */
    public function notifyActiveBookingsOfPackageUpdate(Package $package, $activeBookings, array $impactAnalysis)
    {
        foreach ($activeBookings as $booking) {
            // Create notification for customer
            if ($booking->user) {
                // Send email notification about package changes
                Mail::to($booking->user->email)->queue(
                    new BookingUpdateNotification($booking, $package, $impactAnalysis)
                );
            }

            // Create manager notification if significant changes
            if ($impactAnalysis['requires_price_recalculation']) {
                $managers = User::where('role', 'manager')->get();
                foreach ($managers as $manager) {
                    $this->createManagerNotification(
                        $manager->id,
                        'booking_update',
                        'Package Update Affects Active Booking',
                        "Package '{$package->name}' changes affect booking #{$booking->id}",
                        [
                            'booking_id' => $booking->id,
                            'package_id' => $package->id,
                            'impact_analysis' => $impactAnalysis
                        ],
                        'high',
                        true,
                        route('manager.bookings.show', $booking->id)
                    );
                }
            }
        }
    }

    /**
     * Recalculate booking pricing after package updates
     */
    public function recalculateBookingPricing($bookings, Package $package)
    {
        foreach ($bookings as $booking) {
            $oldTotal = $booking->estimated_total_cost;
            $newTotal = $this->calculateEstimatedBookingTotal($booking);
            
            $booking->update([
                'estimated_total_cost' => $newTotal,
                'advance_payment_amount' => round($newTotal * 0.20, 2)
            ]);

            // Log price change
            $this->logBookingStatusChange(
                $booking,
                'price_updated',
                'price_updated',
                'Booking price recalculated due to package update',
                [
                    'old_total' => $oldTotal,
                    'new_total' => $newTotal,
                    'package_id' => $package->id,
                    'package_name' => $package->name
                ]
            );
        }
    }

    /**
     * Calculate estimated total cost for a booking
     */
    public function calculateEstimatedBookingTotal(Booking $booking)
    {
        $total = 0;

        // Add package price
        if ($booking->package) {
            $total += $booking->package->price;
            
            // Add additional guest charges
            $guestCount = $booking->guest_count ?? $booking->customization_guest_count ?? 0;
            if ($guestCount > $booking->package->max_guests) {
                $additionalGuests = $guestCount - $booking->package->max_guests;
                $total += $additionalGuests * $booking->package->additional_guest_price;
            }
        }

        // Add hall charges (if any additional charges)
        if ($booking->hall && $booking->hall->seasonal_pricing) {
            $seasonalPricing = $booking->hall->seasonal_pricing;
            $bookingDate = $booking->event_date ?? $booking->hall_booking_date;
            
            if ($bookingDate) {
                $month = Carbon::parse($bookingDate)->month;
                $seasonalMultiplier = $seasonalPricing[$month] ?? 1.0;
                $total *= $seasonalMultiplier;
            }
        }

        // Add decoration costs
        if ($booking->customization_decorations_additional) {
            $decorationIds = json_decode($booking->customization_decorations_additional, true) ?? [];
            // Add decoration pricing logic here
        }

        // Add catering costs
        if ($booking->customization_catering_selected_menu_id && $booking->guest_count) {
            // Add catering pricing logic here
        }

        // Add additional services costs
        if ($booking->customization_additional_services_selected) {
            $serviceIds = json_decode($booking->customization_additional_services_selected, true) ?? [];
            // Add services pricing logic here
        }

        return $total;
    }

    /**
     * Handle visit scheduling step
     */
    private function handleVisitSchedulingStep(Booking $booking)
    {
        // Create manager notification for visit approval
        $managers = User::where('role', 'manager')->get();
        foreach ($managers as $manager) {
            $this->createManagerNotification(
                $manager->id,
                'visit_request',
                'New Visit Request Requires Approval',
                "Customer {$booking->contact_name} has requested a venue visit",
                [
                    'booking_id' => $booking->id,
                    'customer_name' => $booking->contact_name,
                    'customer_phone' => $booking->contact_phone,
                    'visit_date' => $booking->visit_date,
                    'visit_time' => $booking->visit_time,
                    'hall_name' => $booking->hall_name
                ],
                'high',
                true,
                route('manager.visits.show', $booking->id)
            );
        }
    }

    /**
     * Handle wedding details step
     */
    private function handleWeddingDetailsStep(Booking $booking)
    {
        // This step should only be accessible after payment confirmation
        if (!$booking->advance_payment_paid) {
            throw new \Exception('Step 5 requires advance payment confirmation');
        }

        // Calculate final total cost
        $finalTotal = $this->calculateEstimatedBookingTotal($booking);
        $booking->update(['final_total_cost' => $finalTotal]);
    }

    /**
     * Handle final summary step
     */
    private function handleFinalSummaryStep(Booking $booking)
    {
        // Generate final contract/invoice
        // Send confirmation emails
        // Update all statistics
        $this->updateBookingStatistics($booking);
    }

    /**
     * Create manager notification
     */
    private function createManagerNotification($managerId, $type, $title, $message, $data = [], $priority = 'medium', $isActionable = false, $actionUrl = null)
    {
        $notification = ManagerNotification::create([
            'manager_id' => $managerId,
            'notification_type' => $type,
            'title' => $title,
            'message' => $message,
            'notification_data' => $data,
            'priority' => $priority,
            'is_actionable' => $isActionable,
            'action_url' => $actionUrl,
            'expires_at' => now()->addDays(7) // Notifications expire after 7 days
        ]);

        // Trigger real-time notification event
        event(new ManagerNotificationCreated($notification));

        return $notification;
    }

    /**
     * Log booking status change
     */
    private function logBookingStatusChange(Booking $booking, $previousStatus, $newStatus, $reason, $metadata = [])
    {
        BookingStatusHistory::create([
            'booking_id' => $booking->id,
            'previous_status' => $previousStatus,
            'new_status' => $newStatus,
            'changed_by' => auth()->id(),
            'change_reason' => $reason,
            'change_metadata' => $metadata,
            'changed_at' => now()
        ]);
    }

    /**
     * Queue synchronization task
     */
    private function queueSync($syncType, $data, $priority = 'medium')
    {
        SyncQueue::create([
            'sync_type' => $syncType,
            'sync_data' => $data,
            'priority' => $priority,
            'scheduled_at' => now()
        ]);
    }

    /**
     * Log integration event
     */
    private function logIntegration($type, $source, $target, $data, $status, $errorMessage = null, $processingTime = null)
    {
        SystemIntegrationLog::create([
            'integration_type' => $type,
            'source_component' => $source,
            'target_component' => $target,
            'integration_data' => $data,
            'status' => $status,
            'error_message' => $errorMessage,
            'processing_time_ms' => $processingTime,
            'triggered_by' => auth()->id()
        ]);
    }

    /**
     * Refresh booking system cache
     */
    private function refreshBookingSystemCache()
    {
        Cache::forget('booking_packages');
        Cache::forget('booking_halls');
        Cache::forget('package_hall_compatibility');
    }

    /**
     * Remove package from cache
     */
    private function removePackageFromCache($packageId)
    {
        Cache::forget("package_{$packageId}");
        $this->refreshBookingSystemCache();
    }

    /**
     * Analyze package changes
     */
    private function analyzePackageChanges(Package $package, array $originalData)
    {
        $changes = [];
        
        $fieldsToCheck = ['price', 'min_guests', 'max_guests', 'additional_guest_price', 'features', 'is_active'];
        
        foreach ($fieldsToCheck as $field) {
            if ($package->$field != $originalData[$field]) {
                $changes[$field] = [
                    'old' => $originalData[$field],
                    'new' => $package->$field
                ];
            }
        }
        
        return $changes;
    }

    /**
     * Update affected bookings
     */
    private function updateAffectedBookings(Package $package, array $changes)
    {
        $activeBookings = Booking::where('package_id', $package->id)
            ->whereIn('status', ['pending', 'confirmed'])
            ->get();

        foreach ($activeBookings as $booking) {
            // Update estimated costs if price changed
            if (isset($changes['price'])) {
                $newTotal = $this->calculateEstimatedBookingTotal($booking);
                $booking->update([
                    'estimated_total_cost' => $newTotal,
                    'advance_payment_amount' => round($newTotal * 0.20, 2)
                ]);
            }
        }
    }

    /**
     * Notify stakeholders of package update
     */
    private function notifyStakeholdersOfPackageUpdate(Package $package, array $changes)
    {
        if (empty($changes)) return;

        // Notify managers
        $managers = User::where('role', 'manager')->get();
        foreach ($managers as $manager) {
            $this->createManagerNotification(
                $manager->id,
                'package_update',
                'Package Updated',
                "Package '{$package->name}' has been updated",
                [
                    'package_id' => $package->id,
                    'changes' => $changes
                ],
                'medium'
            );
        }
    }

    /**
     * Notify managers of new package
     */
    private function notifyManagersOfNewPackage(Package $package)
    {
        $managers = User::where('role', 'manager')->get();
        foreach ($managers as $manager) {
            $this->createManagerNotification(
                $manager->id,
                'package_created',
                'New Package Created',
                "New package '{$package->name}' is now available for bookings",
                [
                    'package_id' => $package->id,
                    'package_name' => $package->name,
                    'package_price' => $package->price
                ],
                'low'
            );
        }
    }

    /**
     * Send customer visit approval notification
     */
    private function sendCustomerVisitApprovalNotification(Booking $booking)
    {
        if ($booking->user && $booking->user->email) {
            // Send email notification
            // Implementation depends on your mail setup
        }
    }

    /**
     * Send customer Step 5 unlock notification
     */
    private function sendCustomerStep5UnlockNotification(Booking $booking)
    {
        if ($booking->user && $booking->user->email) {
            // Send email notification about Step 5 being unlocked
            // Implementation depends on your mail setup
        }
    }

    /**
     * Update booking statistics
     */
    private function updateBookingStatistics(Booking $booking)
    {
        // Update package statistics
        if ($booking->package) {
            $booking->package->increment('booking_count');
            $booking->package->increment('total_revenue', $booking->advance_payment_amount ?? 0);
        }

        // Update hall statistics
        if ($booking->hall) {
            $booking->hall->increment('booking_count');
            $booking->hall->increment('total_revenue', $booking->advance_payment_amount ?? 0);
            $booking->hall->update(['last_booking_date' => $booking->event_date ?? now()]);
        }
    }

    /**
     * Get booking data snapshot for step completion
     */
    private function getBookingDataSnapshot(Booking $booking)
    {
        return [
            'hall_id' => $booking->hall_id,
            'hall_name' => $booking->hall_name,
            'package_id' => $booking->package_id,
            'guest_count' => $booking->guest_count ?? $booking->customization_guest_count,
            'event_date' => $booking->event_date,
            'estimated_total' => $booking->estimated_total_cost,
            'timestamp' => now()
        ];
    }
}