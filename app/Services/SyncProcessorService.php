<?php

namespace App\Services;

use App\Models\SyncQueue;
use App\Models\Package;
use App\Models\Hall;
use App\Models\Booking;
use App\Models\User;
use App\Models\ManagerNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingUpdateNotification;
use App\Mail\ManagerApprovalRequired;
use App\Events\BookingStatusChanged;
use App\Events\PackageUpdated;
use App\Events\ManagerNotificationCreated;

class SyncProcessorService
{
    /**
     * Process a sync queue item
     */
    public function process(SyncQueue $item): bool
    {
        try {
            Log::info("Processing sync item", [
                'id' => $item->id,
                'type' => $item->sync_type,
                'priority' => $item->priority
            ]);

            switch($item->sync_type) {
                case 'package_created':
                    $result = $this->processPackageCreated($item);
                    break;
                case 'package_updated':
                    $result = $this->processPackageUpdated($item);
                    break;
                case 'package_deleted':
                    $result = $this->processPackageDeleted($item);
                    break;
                case 'booking_progression':
                    $result = $this->processBookingProgression($item);
                    break;
                case 'visit_approved':
                    $result = $this->processVisitApproved($item);
                    break;
                case 'payment_confirmed':
                    $result = $this->processPaymentConfirmed($item);
                    break;
                case 'hall_availability_update':
                    $result = $this->processHallAvailabilityUpdate($item);
                    break;
                case 'manager_notification':
                    $result = $this->processManagerNotification($item);
                    break;
                case 'booking_status_change':
                    $result = $this->processBookingStatusChange($item);
                    break;
                case 'cache_refresh':
                    $result = $this->processCacheRefresh($item);
                    break;
                case 'email_notification':
                    $result = $this->processEmailNotification($item);
                    break;
                case 'statistics_update':
                    $result = $this->processStatisticsUpdate($item);
                    break;
                default:
                    $result = $this->processUnknownType($item);
                    break;
            }

            if ($result) {
                Log::info("Successfully processed sync item", [
                    'id' => $item->id,
                    'type' => $item->sync_type
                ]);
            }

            return $result;

        } catch (\Exception $e) {
            Log::error("Error processing sync item", [
                'id' => $item->id,
                'type' => $item->sync_type,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            throw $e;
        }
    }

    /**
     * Process package created sync
     */
    private function processPackageCreated(SyncQueue $item): bool
    {
        $data = $item->sync_data;
        $packageId = $data['package_id'] ?? null;

        if (!$packageId) {
            Log::warning('Package created sync missing package_id', ['item_id' => $item->id]);
            return false;
        }

        $package = Package::find($packageId);
        if (!$package) {
            Log::warning('Package not found for created sync', ['package_id' => $packageId]);
            return false;
        }

        // Refresh package cache
        Cache::forget("package_{$packageId}");
        Cache::forget('booking_packages');
        Cache::forget('package_hall_compatibility');

        // Trigger real-time events
        event(new PackageUpdated($package));

        // Update search indexes if needed
        $this->updatePackageSearchIndex($package);

        return true;
    }

    /**
     * Process package updated sync
     */
    private function processPackageUpdated(SyncQueue $item): bool
    {
        $data = $item->sync_data;
        $packageId = $data['package_id'] ?? null;
        $changes = $data['changes'] ?? [];

        if (!$packageId) {
            return false;
        }

        $package = Package::find($packageId);
        if (!$package) {
            return false;
        }

        // Refresh caches
        Cache::forget("package_{$packageId}");
        Cache::forget('booking_packages');

        // If price changed, update affected bookings
        if (isset($changes['price'])) {
            $this->updateAffectedBookingPrices($package);
        }

        // If guest capacity changed, validate existing bookings
        if (isset($changes['min_guests']) || isset($changes['max_guests'])) {
            $this->validateBookingGuestCounts($package);
        }

        // Trigger events
        event(new PackageUpdated($package));

        return true;
    }

    /**
     * Process package deleted sync
     */
    private function processPackageDeleted(SyncQueue $item): bool
    {
        $data = $item->sync_data;
        $packageId = $data['package_id'] ?? null;

        if (!$packageId) {
            return false;
        }

        // Remove from caches
        Cache::forget("package_{$packageId}");
        Cache::forget('booking_packages');
        Cache::forget('package_hall_compatibility');

        // Handle affected bookings
        $affectedBookings = Booking::where('package_id', $packageId)
            ->whereIn('status', ['pending', 'confirmed'])
            ->get();

        foreach ($affectedBookings as $booking) {
            // Create manager notification about affected booking
            $this->createManagerNotification(
                'package_deleted_impact',
                'Package Deleted - Booking Affected',
                "Booking #{$booking->id} is affected by package deletion",
                [
                    'booking_id' => $booking->id,
                    'deleted_package_id' => $packageId,
                    'customer_name' => $booking->contact_name
                ]
            );
        }

        return true;
    }

    /**
     * Process booking progression sync
     */
    private function processBookingProgression(SyncQueue $item): bool
    {
        $data = $item->sync_data;
        $bookingId = $data['booking_id'] ?? null;

        if (!$bookingId) {
            return false;
        }

        $booking = Booking::find($bookingId);
        if (!$booking) {
            return false;
        }

        // Refresh booking cache
        Cache::forget("booking_{$bookingId}");

        // Update dashboard statistics
        $this->updateDashboardStatistics();

        // Trigger real-time events
        event(new BookingStatusChanged($booking, $data['from_step'] ?? null, $data['to_step'] ?? null));

        return true;
    }

    /**
     * Process visit approved sync
     */
    private function processVisitApproved(SyncQueue $item): bool
    {
        $data = $item->sync_data;
        $bookingId = $data['booking_id'] ?? null;

        if (!$bookingId) {
            return false;
        }

        $booking = Booking::find($bookingId);
        if (!$booking) {
            return false;
        }

        // Send customer notification
        if ($booking->user && $booking->user->email) {
            try {
                // Queue email notification
                SyncQueue::queue('email_notification', [
                    'type' => 'visit_approved',
                    'booking_id' => $bookingId,
                    'recipient_email' => $booking->user->email,
                    'advance_amount' => $data['advance_amount'] ?? 0
                ], 'high');
            } catch (\Exception $e) {
                Log::error('Failed to queue visit approval email', [
                    'booking_id' => $bookingId,
                    'error' => $e->getMessage()
                ]);
            }
        }

        // Update statistics
        $this->updateBookingStatistics($booking);

        return true;
    }

    /**
     * Process payment confirmed sync
     */
    private function processPaymentConfirmed(SyncQueue $item): bool
    {
        $data = $item->sync_data;
        $bookingId = $data['booking_id'] ?? null;

        if (!$bookingId) {
            return false;
        }

        $booking = Booking::find($bookingId);
        if (!$booking) {
            return false;
        }

        // Send customer notification about Step 5 unlock
        if ($booking->user && $booking->user->email) {
            SyncQueue::queue('email_notification', [
                'type' => 'step_5_unlocked',
                'booking_id' => $bookingId,
                'recipient_email' => $booking->user->email
            ], 'high');
        }

        // Update revenue statistics
        $this->updateRevenueStatistics($booking);

        return true;
    }

    /**
     * Process hall availability update sync
     */
    private function processHallAvailabilityUpdate(SyncQueue $item): bool
    {
        $data = $item->sync_data;
        $hallId = $data['hall_id'] ?? null;

        if (!$hallId) {
            return false;
        }

        // Refresh hall availability cache
        Cache::forget("hall_availability_{$hallId}");
        Cache::forget('hall_availability_calendar');

        // Update booking system cache
        Cache::forget('booking_halls');

        return true;
    }

    /**
     * Process manager notification sync
     */
    private function processManagerNotification(SyncQueue $item): bool
    {
        $data = $item->sync_data;
        $notificationId = $data['notification_id'] ?? null;

        if (!$notificationId) {
            return false;
        }

        $notification = ManagerNotification::find($notificationId);
        if (!$notification) {
            return false;
        }

        // Trigger real-time notification event
        event(new ManagerNotificationCreated($notification));

        // Send email if high priority
        if (in_array($notification->priority, ['high', 'urgent'])) {
            $manager = User::find($notification->manager_id);
            if ($manager && $manager->email) {
                SyncQueue::queue('email_notification', [
                    'type' => 'manager_notification',
                    'notification_id' => $notificationId,
                    'recipient_email' => $manager->email
                ], 'medium');
            }
        }

        return true;
    }

    /**
     * Process booking status change sync
     */
    private function processBookingStatusChange(SyncQueue $item): bool
    {
        $data = $item->sync_data;
        $bookingId = $data['booking_id'] ?? null;

        if (!$bookingId) {
            return false;
        }

        $booking = Booking::find($bookingId);
        if (!$booking) {
            return false;
        }

        // Refresh booking cache
        Cache::forget("booking_{$bookingId}");

        // Update dashboard statistics
        $this->updateDashboardStatistics();

        // Trigger events
        event(new BookingStatusChanged(
            $booking, 
            $data['previous_status'] ?? null, 
            $data['new_status'] ?? null
        ));

        return true;
    }

    /**
     * Process cache refresh sync
     */
    private function processCacheRefresh(SyncQueue $item): bool
    {
        $data = $item->sync_data;
        $cacheKeys = $data['cache_keys'] ?? [];

        if (empty($cacheKeys)) {
            // Refresh all booking system caches
            Cache::forget('booking_packages');
            Cache::forget('booking_halls');
            Cache::forget('package_hall_compatibility');
            Cache::forget('dashboard_statistics');
        } else {
            foreach ($cacheKeys as $key) {
                Cache::forget($key);
            }
        }

        return true;
    }

    /**
     * Process email notification sync
     */
    private function processEmailNotification(SyncQueue $item): bool
    {
        $data = $item->sync_data;
        $type = $data['type'] ?? null;
        $recipientEmail = $data['recipient_email'] ?? null;

        if (!$type || !$recipientEmail) {
            return false;
        }

        try {
            switch ($type) {
                case 'visit_approved':
                    $booking = Booking::find($data['booking_id']);
                    if ($booking) {
                        // Send visit approval email
                        // Mail::to($recipientEmail)->queue(new VisitApprovedNotification($booking));
                    }
                    break;

                case 'step_5_unlocked':
                    $booking = Booking::find($data['booking_id']);
                    if ($booking) {
                        // Send step 5 unlock email
                        // Mail::to($recipientEmail)->queue(new Step5UnlockedNotification($booking));
                    }
                    break;

                case 'manager_notification':
                    $notification = ManagerNotification::find($data['notification_id']);
                    if ($notification) {
                        // Send manager notification email
                        // Mail::to($recipientEmail)->queue(new ManagerNotificationEmail($notification));
                    }
                    break;

                default:
                    Log::warning('Unknown email notification type', ['type' => $type]);
                    return false;
            }

            return true;

        } catch (\Exception $e) {
            Log::error('Failed to send email notification', [
                'type' => $type,
                'recipient' => $recipientEmail,
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }

    /**
     * Process statistics update sync
     */
    private function processStatisticsUpdate(SyncQueue $item): bool
    {
        $data = $item->sync_data;
        $type = $data['type'] ?? 'general';

        switch ($type) {
            case 'booking':
                $this->updateBookingStatistics();
                break;
            case 'revenue':
                $this->updateRevenueStatistics();
                break;
            case 'dashboard':
                $this->updateDashboardStatistics();
                break;
            default:
                $this->updateAllStatistics();
        }

        return true;
    }

    /**
     * Process unknown sync type
     */
    private function processUnknownType(SyncQueue $item): bool
    {
        Log::warning('Unknown sync type encountered', [
            'id' => $item->id,
            'type' => $item->sync_type,
            'data' => $item->sync_data
        ]);

        return false;
    }

    /**
     * Helper methods
     */

    private function updateAffectedBookingPrices(Package $package)
    {
        $bookings = Booking::where('package_id', $package->id)
            ->whereIn('status', ['pending', 'confirmed'])
            ->get();

        foreach ($bookings as $booking) {
            $oldTotal = $booking->estimated_total_cost;
            $newTotal = $this->calculateBookingTotal($booking);
            
            $booking->update([
                'estimated_total_cost' => $newTotal,
                'advance_payment_amount' => round($newTotal * 0.20, 2)
            ]);

            // Queue notification about price change
            SyncQueue::queue('booking_status_change', [
                'booking_id' => $booking->id,
                'change_type' => 'price_update',
                'old_total' => $oldTotal,
                'new_total' => $newTotal
            ], 'medium');
        }
    }

    private function validateBookingGuestCounts(Package $package)
    {
        $bookings = Booking::where('package_id', $package->id)
            ->whereIn('status', ['pending', 'confirmed'])
            ->get();

        foreach ($bookings as $booking) {
            $guestCount = $booking->guest_count ?? $booking->customization_guest_count ?? 0;
            
            if ($guestCount < $package->min_guests || $guestCount > $package->max_guests) {
                // Create manager notification about guest count mismatch
                $this->createManagerNotification(
                    'guest_count_mismatch',
                    'Guest Count Validation Required',
                    "Booking #{$booking->id} guest count ({$guestCount}) doesn't match updated package limits",
                    [
                        'booking_id' => $booking->id,
                        'guest_count' => $guestCount,
                        'package_min' => $package->min_guests,
                        'package_max' => $package->max_guests
                    ]
                );
            }
        }
    }

    private function calculateBookingTotal(Booking $booking)
    {
        // Simplified calculation - implement full logic as needed
        $total = $booking->package ? $booking->package->price : 0;
        
        // Add additional guest charges
        $guestCount = $booking->guest_count ?? $booking->customization_guest_count ?? 0;
        if ($booking->package && $guestCount > $booking->package->max_guests) {
            $additionalGuests = $guestCount - $booking->package->max_guests;
            $total += $additionalGuests * $booking->package->additional_guest_price;
        }

        return $total;
    }

    private function updatePackageSearchIndex(Package $package)
    {
        // Update search index if using search functionality
        // This could integrate with Elasticsearch, Algolia, etc.
    }

    private function updateBookingStatistics(Booking $booking = null)
    {
        // Update booking-related statistics
        Cache::forget('booking_statistics');
        Cache::forget('dashboard_statistics');
    }

    private function updateRevenueStatistics(Booking $booking = null)
    {
        // Update revenue statistics
        Cache::forget('revenue_statistics');
        Cache::forget('dashboard_statistics');
    }

    private function updateDashboardStatistics()
    {
        // Update dashboard statistics
        Cache::forget('dashboard_statistics');
        Cache::forget('manager_dashboard_stats');
        Cache::forget('admin_dashboard_stats');
    }

    private function updateAllStatistics()
    {
        $this->updateBookingStatistics();
        $this->updateRevenueStatistics();
        $this->updateDashboardStatistics();
    }

    private function createManagerNotification($type, $title, $message, $data = [])
    {
        $managers = User::where('role', 'manager')->get();
        
        foreach ($managers as $manager) {
            $notification = ManagerNotification::create([
                'manager_id' => $manager->id,
                'notification_type' => $type,
                'title' => $title,
                'message' => $message,
                'notification_data' => $data,
                'priority' => 'medium',
                'is_actionable' => true
            ]);

            // Queue real-time notification
            SyncQueue::queue('manager_notification', [
                'notification_id' => $notification->id
            ], 'high');
        }
    }
}