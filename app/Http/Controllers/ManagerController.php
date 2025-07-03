<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\User;
use App\Models\ManagerMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ManagerController extends Controller
{
    public function __construct()
    {
        // Middleware is applied at route level in Laravel 11
    }

    /**
     * Manager dashboard
     */
    public function dashboard()
    {
        $pendingVisits = Booking::where('visit_submitted', true)
            ->where('visit_confirmed', false)
            ->with(['user', 'hall'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        $confirmedVisits = Booking::where('visit_confirmed', true)
            ->where('advance_payment_paid', false)
            ->with(['user', 'hall'])
            ->orderBy('visit_confirmed_at', 'desc')
            ->take(10)
            ->get();

        $stats = [
            'pending_visits' => Booking::where('visit_submitted', true)->where('visit_confirmed', false)->count(),
            'confirmed_visits' => Booking::where('visit_confirmed', true)->where('advance_payment_paid', false)->count(),
            'completed_bookings' => Booking::where('advance_payment_paid', true)->count(),
            'total_revenue' => Booking::where('advance_payment_paid', true)->sum('advance_payment_amount'),
        ];

        return view('manager.dashboard', compact('pendingVisits', 'confirmedVisits', 'stats'));
    }

    /**
     * Get all visit requests (pending and approved) with history
     */
    public function getPendingVisits()
    {
        $status = request('status', 'all');
        
        $query = Booking::where('visit_submitted', true)
            ->with(['user', 'hall', 'package', 'visitConfirmedBy']);

        // Filter by status if specified
        if ($status === 'pending') {
            $query->where('visit_confirmed', false);
        } elseif ($status === 'approved') {
            $query->where('visit_confirmed', true);
        }
        // 'all' shows both pending and approved

        $visits = $query->orderBy('created_at', 'desc')->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $visits->items(),
            'pagination' => [
                'current_page' => $visits->currentPage(),
                'last_page' => $visits->lastPage(),
                'per_page' => $visits->perPage(),
                'total' => $visits->total()
            ]
        ]);
    }

    /**
     * Get all visit requests for the visits section
     */
    public function getAllVisits()
    {
        $visits = Booking::where('visit_submitted', true)
            ->with(['user', 'hall', 'package', 'visitConfirmedBy'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $visits
        ]);
    }

    /**
     * Get visit statistics
     */
    public function getVisitStats()
    {
        $stats = [
            'pending' => Booking::where('visit_submitted', true)->where('visit_confirmed', false)->count(),
            'confirmed' => Booking::where('visit_confirmed', true)->count(),
            'payment_pending' => Booking::where('visit_confirmed', true)->where('advance_payment_paid', false)->count(),
            'completed' => Booking::where('advance_payment_paid', true)->count(),
        ];

        return response()->json($stats);
    }

    /**
     * Get visit details
     */
    public function getVisitDetails($visitId)
    {
        $booking = Booking::with(['user', 'hall', 'package', 'weddingType', 'visitConfirmedBy'])
            ->findOrFail($visitId);

        return response()->json($booking);
    }

    /**
     * Confirm a visit request - MANAGER ONLY ACTION
     * This is the critical step that unlocks advance payment requirement
     */
    public function approveVisit(Request $request, $id)
    {
        try {
            $request->validate([
                'notes' => 'nullable|string|max:1000'
            ]);

            DB::beginTransaction();

            $booking = Booking::findOrFail($id);
            
            // Strict validation: Visit must be submitted first (Step 4 completed)
            if (!$booking->visit_submitted) {
                return response()->json([
                    'success' => false,
                    'message' => 'Visit request has not been submitted yet. Customer must complete Step 4 first.'
                ], 400);
            }

            // Prevent double confirmation
            if ($booking->visit_confirmed) {
                return response()->json([
                    'success' => false,
                    'message' => 'Visit has already been confirmed.'
                ], 400);
            }

            // Ensure only managers can approve visits
            if (!Auth::user() || Auth::user()->role !== 'manager') {
                return response()->json([
                    'success' => false,
                    'message' => 'Only managers can approve visit requests.'
                ], 403);
            }

            // CRITICAL: Manager confirms visit - this triggers advance payment requirement
            $booking->confirmVisit(Auth::user(), $request->input('notes'));

            // Ensure Step 5 remains locked until advance payment is made
            $booking->step5_unlocked = false;
            $booking->save();

            DB::commit();

            Log::info('Visit confirmed by manager - Advance payment now required', [
                'booking_id' => $booking->id,
                'manager_id' => Auth::id(),
                'manager_name' => Auth::user()->name,
                'advance_payment_amount' => $booking->advance_payment_amount,
                'step5_locked' => true,
                'customer_id' => $booking->user_id,
                'customer_name' => $booking->user->full_name ?? 'Unknown'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Visit confirmed successfully! Customer can now see visit details and advance payment requirement.',
                'booking' => $booking->fresh(['user', 'hall', 'package']),
                'next_steps' => [
                    'visit_confirmed' => true,
                    'advance_payment_required' => true,
                    'advance_payment_amount' => $booking->advance_payment_amount,
                    'step5_accessible' => false,
                    'message' => 'Customer must pay 20% advance before accessing Step 5'
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to confirm visit', [
                'booking_id' => $id,
                'manager_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to confirm visit: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reject a visit request - MANAGER ONLY ACTION
     * This resets the customer back to Step 4 to resubmit
     */
    public function rejectVisit(Request $request, $id)
    {
        try {
            $request->validate([
                'reason' => 'required|string|max:1000'
            ]);

            DB::beginTransaction();

            $booking = Booking::findOrFail($id);
            
            // Strict validation: Visit must be submitted first
            if (!$booking->visit_submitted) {
                return response()->json([
                    'success' => false,
                    'message' => 'Visit has not been submitted yet.'
                ], 400);
            }

            // Cannot reject already confirmed visits
            if ($booking->visit_confirmed) {
                return response()->json([
                    'success' => false,
                    'message' => 'Visit has already been confirmed and cannot be rejected.'
                ], 400);
            }

            // Ensure only managers can reject visits
            if (!Auth::user() || Auth::user()->role !== 'manager') {
                return response()->json([
                    'success' => false,
                    'message' => 'Only managers can reject visit requests.'
                ], 403);
            }

            // CRITICAL: Reset visit submission - customer must resubmit Step 4
            $booking->visit_submitted = false;
            $booking->visit_confirmed = false;
            $booking->visit_confirmed_at = null;
            $booking->visit_confirmed_by = null;
            $booking->visit_confirmation_notes = 'REJECTED: ' . $request->input('reason');
            
            // Reset advance payment requirements
            $booking->advance_payment_required = false;
            $booking->advance_payment_amount = 0;
            $booking->advance_payment_paid = false;
            $booking->advance_payment_paid_at = null;
            $booking->advance_payment_method = null;
            $booking->advance_payment_notes = null;
            
            // Lock Step 5 access
            $booking->step5_unlocked = false;
            
            $booking->save();

            DB::commit();

            Log::info('Visit rejected by manager - Customer must resubmit Step 4', [
                'booking_id' => $booking->id,
                'manager_id' => Auth::id(),
                'manager_name' => Auth::user()->name,
                'reason' => $request->input('reason'),
                'customer_id' => $booking->user_id,
                'customer_name' => $booking->user->full_name ?? 'Unknown',
                'reset_to_step4' => true
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Visit request rejected. Customer must resubmit visit request in Step 4.',
                'booking' => $booking->fresh(['user', 'hall', 'package']),
                'next_steps' => [
                    'visit_submitted' => false,
                    'visit_confirmed' => false,
                    'advance_payment_required' => false,
                    'step5_accessible' => false,
                    'message' => 'Customer must resubmit Step 4 with new visit details'
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to reject visit', [
                'booking_id' => $id,
                'manager_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to reject visit: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mark advance payment as paid - MANAGER ONLY ACTION
     * This is the final step that unlocks Step 5 access
     */
    public function confirmPayment(Request $request, $id)
    {
        return $this->markDepositPaid($request, $id);
    }

    /**
     * Mark advance payment as paid - MANAGER ONLY ACTION
     * This is the final step that unlocks Step 5 access
     */
    public function markDepositPaid(Request $request, $id)
    {
        try {
            $request->validate([
                'payment_method' => 'required|string|max:100',
                'notes' => 'nullable|string|max:1000'
            ]);

            DB::beginTransaction();

            $booking = Booking::findOrFail($id);
            
            // Strict validation: Visit must be confirmed first
            if (!$booking->visit_confirmed) {
                return response()->json([
                    'success' => false,
                    'message' => 'Visit must be confirmed by manager before marking payment as paid.'
                ], 400);
            }

            // Prevent double payment marking
            if ($booking->advance_payment_paid) {
                return response()->json([
                    'success' => false,
                    'message' => 'Advance payment has already been marked as paid.'
                ], 400);
            }

            // Ensure only managers can mark payments
            if (!Auth::user() || Auth::user()->role !== 'manager') {
                return response()->json([
                    'success' => false,
                    'message' => 'Only managers can mark advance payments as paid.'
                ], 403);
            }

            // Validate advance payment amount exists
            if (!$booking->advance_payment_required || $booking->advance_payment_amount <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'No advance payment is required for this booking.'
                ], 400);
            }

            // CRITICAL: Mark payment as paid and unlock Step 5
            $booking->markAdvancePaymentPaid(
                $request->input('payment_method'),
                $request->input('notes')
            );

            // Explicitly unlock Step 5 - this is the key to accessing final step
            $booking->step5_unlocked = true;
            $booking->save();

            DB::commit();

            Log::info('Advance payment marked as paid - Step 5 now accessible', [
                'booking_id' => $booking->id,
                'manager_id' => Auth::id(),
                'manager_name' => Auth::user()->name,
                'payment_method' => $request->input('payment_method'),
                'amount' => $booking->advance_payment_amount,
                'customer_id' => $booking->user_id,
                'customer_name' => $booking->user->full_name ?? 'Unknown',
                'step5_unlocked' => true,
                'workflow_complete' => 'Customer can now access Step 5'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Advance payment marked as paid. Customer can now access Step 5 to complete final wedding details.',
                'booking' => $booking->fresh(['user', 'hall', 'package']),
                'workflow_status' => [
                    'visit_submitted' => true,
                    'visit_confirmed' => true,
                    'advance_payment_paid' => true,
                    'step5_accessible' => true,
                    'remaining_amount' => $booking->getRemainingAmount(),
                    'message' => 'All requirements met - Step 5 is now accessible'
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to mark advance payment as paid', [
                'booking_id' => $id,
                'manager_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to mark payment as paid: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Visit schedules page
     */
    public function visitSchedules()
    {
        $bookings = Booking::where('visit_submitted', true)
            ->with(['user', 'hall', 'package', 'visitConfirmedBy'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('manager.visit-schedules', compact('bookings'));
    }

    /**
     * Visit requests page (alias for visitSchedules)
     */
    public function visitRequests()
    {
        return $this->visitSchedules();
    }

    /**
     * Update visit (legacy method for compatibility)
     */
    public function updateVisit(Request $request, $visitId)
    {
        return $this->approveVisit($request, $visitId);
    }

    /**
     * Get dashboard stats for AJAX updates
     */
    public function getDashboardStats()
    {
        $stats = [
            'total_halls' => \App\Models\Hall::where('is_active', true)->count(),
            'pending_visits' => Booking::where('visit_submitted', true)->where('visit_confirmed', false)->count(),
            'confirmed_visits' => Booking::where('visit_confirmed', true)->where('advance_payment_paid', false)->count(),
            'completed_bookings' => Booking::where('advance_payment_paid', true)->count(),
            'upcoming_events' => Booking::where('event_date', '>=', now())->where('advance_payment_paid', true)->count(),
            'total_revenue' => Booking::where('advance_payment_paid', true)->sum('advance_payment_amount'),
        ];

        return response()->json([
            'success' => true,
            'stats' => $stats
        ]);
    }

    /**
     * Get calendar events for the manager dashboard
     */
    public function getCalendarEvents(Request $request)
    {
        $startDate = $request->input('start', now()->startOfMonth());
        $endDate = $request->input('end', now()->endOfMonth());

        $events = [];

        // Get visit dates
        $visits = Booking::where('visit_submitted', true)
            ->where('visit_date', '>=', $startDate)
            ->where('visit_date', '<=', $endDate)
            ->with(['user', 'hall'])
            ->get();

        foreach ($visits as $visit) {
            $events[] = [
                'id' => 'visit_' . $visit->id,
                'title' => 'Visit: ' . $visit->user->full_name,
                'start' => $visit->visit_date,
                'type' => 'visit',
                'status' => $visit->visit_confirmed ? 'confirmed' : 'pending',
                'hall' => $visit->hall->name ?? 'N/A'
            ];
        }

        // Get wedding events
        $weddings = Booking::where('advance_payment_paid', true)
            ->where('event_date', '>=', $startDate)
            ->where('event_date', '<=', $endDate)
            ->with(['user', 'hall'])
            ->get();

        foreach ($weddings as $wedding) {
            $events[] = [
                'id' => 'wedding_' . $wedding->id,
                'title' => 'Wedding: ' . $wedding->user->full_name,
                'start' => $wedding->event_date,
                'type' => 'wedding',
                'status' => 'confirmed',
                'hall' => $wedding->hall->name ?? 'N/A'
            ];
        }

        return response()->json([
            'success' => true,
            'events' => $events
        ]);
    }

    /**
     * Get all halls with booking statistics
     */
    public function getHalls()
    {
        try {
            $halls = \App\Models\Hall::where('is_active', true)
                ->orderBy('name')
                ->get();

            $hallsWithStats = $halls->map(function ($hall) {
                $activeBookings = Booking::where('hall_id', $hall->id)
                    ->where('visit_submitted', true)
                    ->count();

                $confirmedBookings = Booking::where('hall_id', $hall->id)
                    ->where('advance_payment_paid', true)
                    ->where('event_date', '>=', now())
                    ->count();

                $pendingVisits = Booking::where('hall_id', $hall->id)
                    ->where('visit_submitted', true)
                    ->where('visit_confirmed', false)
                    ->count();

                return [
                    'id' => $hall->id,
                    'name' => $hall->name,
                    'description' => $hall->description,
                    'capacity' => $hall->capacity,
                    'price' => $hall->price,
                    'image' => $hall->image,
                    'features' => json_decode($hall->features, true),
                    'is_active' => $hall->is_active,
                    'stats' => [
                        'active_bookings' => $activeBookings,
                        'confirmed_bookings' => $confirmedBookings,
                        'pending_visits' => $pendingVisits,
                        'availability' => $activeBookings > 0 ? 'busy' : 'available'
                    ]
                ];
            });

            return response()->json([
                'success' => true,
                'halls' => $hallsWithStats
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to get halls', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to load halls'
            ], 500);
        }
    }

    /**
     * Get hall details with booking information
     */
    public function getHallDetails($hallId)
    {
        try {
            $hall = \App\Models\Hall::findOrFail($hallId);
            
            $upcomingBookings = Booking::where('hall_id', $hallId)
                ->where('advance_payment_paid', true)
                ->where('event_date', '>=', now())
                ->with(['user'])
                ->orderBy('event_date')
                ->get();

            $pendingVisits = Booking::where('hall_id', $hallId)
                ->where('visit_submitted', true)
                ->where('visit_confirmed', false)
                ->with(['user'])
                ->orderBy('visit_date')
                ->get();

            return response()->json([
                'success' => true,
                'hall' => $hall,
                'upcoming_bookings' => $upcomingBookings,
                'pending_visits' => $pendingVisits,
                'stats' => [
                    'total_bookings' => $upcomingBookings->count(),
                    'pending_visits' => $pendingVisits->count(),
                    'revenue_this_year' => Booking::where('hall_id', $hallId)
                        ->where('advance_payment_paid', true)
                        ->whereYear('event_date', now()->year)
                        ->sum('advance_payment_amount')
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Hall not found'
            ], 404);
        }
    }

    /**
     * Get booking workflow status for a specific booking
     * This helps frontend understand what step the customer is on
     */
    public function getBookingWorkflowStatus($bookingId)
    {
        try {
            $booking = Booking::with(['user', 'hall', 'package'])->findOrFail($bookingId);
            
            $workflowStatus = [
                'booking_id' => $booking->id,
                'customer_name' => $booking->user->full_name ?? 'Unknown',
                'step4_completed' => $booking->visit_submitted,
                'visit_confirmed' => $booking->visit_confirmed,
                'advance_payment_required' => $booking->advance_payment_required,
                'advance_payment_amount' => $booking->advance_payment_amount,
                'advance_payment_paid' => $booking->advance_payment_paid,
                'step5_accessible' => $booking->canAccessStep5(),
                'current_stage' => $this->determineCurrentStage($booking),
                'next_action' => $this->determineNextAction($booking),
                'remaining_amount' => $booking->getRemainingAmount(),
                'visit_details' => [
                    'date' => $booking->visit_date,
                    'time' => $booking->visit_time,
                    'confirmed_at' => $booking->visit_confirmed_at,
                    'confirmed_by' => $booking->visitConfirmedBy->name ?? null,
                    'notes' => $booking->visit_confirmation_notes
                ]
            ];

            return response()->json([
                'success' => true,
                'workflow_status' => $workflowStatus
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Booking not found'
            ], 404);
        }
    }

    /**
     * Determine the current stage of the booking workflow
     */
    private function determineCurrentStage(Booking $booking): string
    {
        if (!$booking->visit_submitted) {
            return 'step4_incomplete';
        } elseif ($booking->visit_submitted && !$booking->visit_confirmed) {
            return 'awaiting_visit_confirmation';
        } elseif ($booking->visit_confirmed && !$booking->advance_payment_paid) {
            return 'awaiting_advance_payment';
        } elseif ($booking->advance_payment_paid && $booking->step5_unlocked) {
            return 'step5_accessible';
        } else {
            return 'workflow_error';
        }
    }

    /**
     * Determine the next action required in the workflow
     */
    private function determineNextAction(Booking $booking): string
    {
        if (!$booking->visit_submitted) {
            return 'Customer must complete and submit Step 4';
        } elseif ($booking->visit_submitted && !$booking->visit_confirmed) {
            return 'Manager must approve or reject visit request';
        } elseif ($booking->visit_confirmed && !$booking->advance_payment_paid) {
            return 'Customer must pay 20% advance, then manager marks as paid';
        } elseif ($booking->advance_payment_paid && $booking->step5_unlocked) {
            return 'Customer can access Step 5 to complete wedding details';
        } else {
            return 'Workflow validation required';
        }
    }

    /**
     * Validate that the booking workflow is in correct state
     * This method helps ensure data integrity
     */
    public function validateBookingWorkflow($bookingId)
    {
        try {
            $booking = Booking::findOrFail($bookingId);
            
            $validationResults = [
                'booking_id' => $booking->id,
                'is_valid' => true,
                'errors' => [],
                'warnings' => []
            ];

            // Check Step 4 completion
            if ($booking->visit_confirmed && !$booking->visit_submitted) {
                $validationResults['is_valid'] = false;
                $validationResults['errors'][] = 'Visit is confirmed but not submitted - data inconsistency';
            }

            // Check advance payment logic
            if ($booking->advance_payment_paid && !$booking->visit_confirmed) {
                $validationResults['is_valid'] = false;
                $validationResults['errors'][] = 'Advance payment marked as paid but visit not confirmed';
            }

            // Check Step 5 access logic
            if ($booking->step5_unlocked && (!$booking->visit_confirmed || !$booking->advance_payment_paid)) {
                $validationResults['is_valid'] = false;
                $validationResults['errors'][] = 'Step 5 is unlocked but prerequisites not met';
            }

            // Check advance payment amount
            if ($booking->visit_confirmed && $booking->advance_payment_amount <= 0) {
                $validationResults['warnings'][] = 'Visit confirmed but advance payment amount is zero';
            }

            return response()->json([
                'success' => true,
                'validation' => $validationResults
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Booking not found'
            ], 404);
        }
    }

    /**
     * Get notifications for the manager
     */
    public function getNotifications(Request $request)
    {
        try {
            $limit = $request->input('limit', 10);
            
            // Get recent visit requests (pending)
            $pendingVisits = Booking::where('visit_submitted', true)
                ->where('visit_confirmed', false)
                ->with(['user', 'hall'])
                ->orderBy('created_at', 'desc')
                ->take($limit)
                ->get();

            // Get recent confirmed visits awaiting payment
            $awaitingPayment = Booking::where('visit_confirmed', true)
                ->where('advance_payment_paid', false)
                ->with(['user', 'hall'])
                ->orderBy('visit_confirmed_at', 'desc')
                ->take($limit)
                ->get();

            $notifications = [];

            // Add pending visit notifications
            foreach ($pendingVisits as $visit) {
                $notifications[] = [
                    'id' => 'visit_' . $visit->id,
                    'type' => 'visit_request',
                    'title' => 'New Visit Request',
                    'message' => $visit->user->full_name . ' requested a visit to ' . ($visit->hall->name ?? $visit->hall_name),
                    'time' => $visit->created_at->diffForHumans(),
                    'timestamp' => $visit->created_at->timestamp,
                    'booking_id' => $visit->id,
                    'user_name' => $visit->user->full_name,
                    'hall_name' => $visit->hall->name ?? $visit->hall_name,
                    'visit_date' => $visit->visit_date ? $visit->visit_date->format('M d, Y') : 'N/A',
                    'visit_time' => $visit->visit_time ?? 'N/A',
                    'priority' => 'high',
                    'action_required' => true,
                    'actions' => [
                        'approve' => route('manager.visit.approve', $visit->id),
                        'reject' => route('manager.visit.reject', $visit->id)
                    ]
                ];
            }

            // Add payment pending notifications
            foreach ($awaitingPayment as $booking) {
                $notifications[] = [
                    'id' => 'payment_' . $booking->id,
                    'type' => 'payment_pending',
                    'title' => 'Payment Confirmation Needed',
                    'message' => $booking->user->full_name . ' needs advance payment confirmation for ' . ($booking->hall->name ?? $booking->hall_name),
                    'time' => $booking->visit_confirmed_at->diffForHumans(),
                    'timestamp' => $booking->visit_confirmed_at->timestamp,
                    'booking_id' => $booking->id,
                    'user_name' => $booking->user->full_name,
                    'hall_name' => $booking->hall->name ?? $booking->hall_name,
                    'amount' => $booking->advance_payment_amount,
                    'priority' => 'medium',
                    'action_required' => true,
                    'actions' => [
                        'mark_paid' => route('manager.deposit.paid', $booking->id)
                    ]
                ];
            }

            // Sort notifications by timestamp (newest first)
            usort($notifications, function($a, $b) {
                return $b['timestamp'] - $a['timestamp'];
            });

            // Limit the results
            $notifications = array_slice($notifications, 0, $limit);

            return response()->json([
                'success' => true,
                'notifications' => $notifications,
                'total_count' => count($notifications),
                'unread_count' => $pendingVisits->count() + $awaitingPayment->count()
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to get notifications', [
                'manager_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to load notifications'
            ], 500);
        }
    }

    /**
     * Get notification count for the bell icon
     */
    public function getNotificationCount()
    {
        try {
            $pendingVisits = Booking::where('visit_submitted', true)
                ->where('visit_confirmed', false)
                ->count();

            $awaitingPayment = Booking::where('visit_confirmed', true)
                ->where('advance_payment_paid', false)
                ->count();

            $totalCount = $pendingVisits + $awaitingPayment;

            return response()->json([
                'success' => true,
                'count' => $totalCount,
                'breakdown' => [
                    'pending_visits' => $pendingVisits,
                    'awaiting_payment' => $awaitingPayment
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to get notification count', [
                'manager_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'count' => 0
            ]);
        }
    }

    /**
     * Mark all notifications as read (placeholder for future implementation)
     */
    public function markNotificationsRead()
    {
        // For now, this is a placeholder since we're using real-time data
        // In a full implementation, you'd have a notifications table to track read status
        return response()->json([
            'success' => true,
            'message' => 'All notifications marked as read'
        ]);
    }

    /**
     * Mark single notification as read (placeholder for future implementation)
     */
    public function markNotificationRead($id)
    {
        // For now, this is a placeholder since we're using real-time data
        // In a full implementation, you'd have a notifications table to track read status
        return response()->json([
            'success' => true,
            'message' => 'Notification marked as read'
        ]);
    }

    /**
     * Bulk approve all pending payment confirmations
     */
    public function bulkApprovePayments(Request $request)
    {
        try {
            DB::beginTransaction();

            $pendingPayments = Booking::where('visit_confirmed', true)
                ->where('advance_payment_paid', false)
                ->get();

            if ($pendingPayments->count() === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'No pending payments to approve'
                ]);
            }

            $approvedCount = 0;
            foreach ($pendingPayments as $booking) {
                try {
                    $booking->markAdvancePaymentPaid('bulk_approval', 'Bulk approved by manager');
                    $approvedCount++;
                } catch (\Exception $e) {
                    Log::warning('Failed to bulk approve payment for booking ' . $booking->id, [
                        'error' => $e->getMessage()
                    ]);
                }
            }

            DB::commit();

            Log::info('Bulk payment approval completed', [
                'manager_id' => Auth::id(),
                'total_pending' => $pendingPayments->count(),
                'approved_count' => $approvedCount
            ]);

            return response()->json([
                'success' => true,
                'message' => "Successfully approved {$approvedCount} out of {$pendingPayments->count()} pending payments",
                'approved_count' => $approvedCount,
                'total_count' => $pendingPayments->count()
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Bulk payment approval failed', [
                'manager_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to bulk approve payments: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export bookings to CSV
     */
    public function exportBookings(Request $request)
    {
        try {
            $bookings = Booking::where('advance_payment_paid', true)
                ->with(['user', 'hall', 'package'])
                ->orderBy('event_date', 'desc')
                ->get();

            $filename = 'bookings-export-' . now()->format('Y-m-d') . '.csv';
            
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];

            $callback = function() use ($bookings) {
                $file = fopen('php://output', 'w');
                
                // CSV Headers
                fputcsv($file, [
                    'Booking ID',
                    'Couple Name',
                    'Email',
                    'Hall',
                    'Wedding Date',
                    'Guests',
                    'Total Amount',
                    'Advance Paid',
                    'Payment Method',
                    'Status',
                    'Booking Date'
                ]);

                // CSV Data
                foreach ($bookings as $booking) {
                    fputcsv($file, [
                        'WED' . str_pad($booking->id, 4, '0', STR_PAD_LEFT),
                        $booking->user->full_name ?? ($booking->contact_name ?? 'N/A'),
                        $booking->contact_email ?? ($booking->user->email ?? 'N/A'),
                        $booking->hall->name ?? $booking->hall_name ?? 'N/A',
                        $booking->event_date ? $booking->event_date->format('Y-m-d') : 'N/A',
                        $booking->guest_count ?? $booking->customization_guest_count ?? 'N/A',
                        number_format($booking->total_amount ?? $booking->package_price ?? 0, 2),
                        number_format($booking->advance_payment_amount ?? 0, 2),
                        $booking->advance_payment_method ?? 'N/A',
                        $booking->status ?? 'confirmed',
                        $booking->created_at->format('Y-m-d H:i:s')
                    ]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);

        } catch (\Exception $e) {
            Log::error('Failed to export bookings', [
                'manager_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to export bookings'
            ], 500);
        }
    }

    /**
     * Generate invoice for a booking
     */
    public function generateInvoice($bookingId)
    {
        try {
            $booking = Booking::with(['user', 'hall', 'package', 'bookingDecorations.decoration', 'bookingAdditionalServices.service', 'bookingCatering'])
                ->findOrFail($bookingId);

            // For now, return a simple HTML invoice
            // In a full implementation, you'd generate a PDF
            return view('manager.invoice', compact('booking'));

        } catch (\Exception $e) {
            Log::error('Failed to generate invoice', [
                'booking_id' => $bookingId,
                'manager_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to generate invoice'
            ], 500);
        }
    }

    /**
     * Update manager profile
     */
    public function updateProfile(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:users,email,' . Auth::id(),
                'phone' => 'nullable|string|max:20',
                'profile_photo' => 'nullable|image|max:2048'
            ]);

            $user = Auth::user();
            $data = $request->only(['name', 'email', 'phone']);

            // Handle profile photo upload
            if ($request->hasFile('profile_photo')) {
                $file = $request->file('profile_photo');
                $path = $file->store('profile-photos', 'public');
                $data['profile_photo_path'] = $path;
            }

            $user->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully',
                'user' => $user->fresh()
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to update manager profile', [
                'manager_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update profile: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get messages for the manager
     */
    public function getMessages(Request $request)
    {
        try {
            $type = $request->get('type', 'all');
            $limit = $request->get('limit', 20);
            $page = $request->get('page', 1);

            $query = ManagerMessage::forManager(Auth::id())
                ->with(['fromUser', 'booking'])
                ->orderBy('created_at', 'desc');

            // Filter by type
            if ($type !== 'all') {
                if ($type === 'unread') {
                    $query->unread();
                } else {
                    $query->byType($type);
                }
            }

            $messages = $query->paginate($limit, ['*'], 'page', $page);

            return response()->json([
                'success' => true,
                'messages' => $messages->items(),
                'pagination' => [
                    'current_page' => $messages->currentPage(),
                    'last_page' => $messages->lastPage(),
                    'per_page' => $messages->perPage(),
                    'total' => $messages->total(),
                    'has_more' => $messages->hasMorePages()
                ],
                'unread_count' => ManagerMessage::forManager(Auth::id())->unread()->count()
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting messages', [
                'manager_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error loading messages'
            ], 500);
        }
    }

    /**
     * Mark message as read
     */
    public function markMessageRead($id)
    {
        try {
            $message = ManagerMessage::forManager(Auth::id())->findOrFail($id);
            $message->markAsRead();

            return response()->json([
                'success' => true,
                'message' => 'Message marked as read'
            ]);

        } catch (\Exception $e) {
            Log::error('Error marking message as read', [
                'message_id' => $id,
                'manager_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error marking message as read'
            ], 500);
        }
    }

    /**
     * Mark all messages as read
     */
    public function markAllMessagesRead()
    {
        try {
            ManagerMessage::forManager(Auth::id())
                ->unread()
                ->update([
                    'is_read' => true,
                    'read_at' => now()
                ]);

            return response()->json([
                'success' => true,
                'message' => 'All messages marked as read'
            ]);

        } catch (\Exception $e) {
            Log::error('Error marking all messages as read', [
                'manager_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error marking messages as read'
            ], 500);
        }
    }

    /**
     * Send a reply to a customer inquiry
     */
    public function replyToMessage(Request $request, $id)
    {
        try {
            $request->validate([
                'reply_message' => 'required|string|max:2000'
            ]);

            $originalMessage = ManagerMessage::forManager(Auth::id())->findOrFail($id);
            
            // Mark original as read
            $originalMessage->markAsRead();

            // For now, we'll just log the reply. In a full system, you'd send an email or create a customer notification
            Log::info('Manager replied to message', [
                'original_message_id' => $id,
                'manager_id' => Auth::id(),
                'reply' => $request->reply_message,
                'customer_id' => $originalMessage->from_user_id
            ]);

            // Create a system message to track the reply
            ManagerMessage::createSystemMessage(
                'Reply Sent: ' . $originalMessage->subject,
                'Reply sent to customer: ' . $request->reply_message,
                'normal',
                [
                    'original_message_id' => $id,
                    'reply_to_customer' => $originalMessage->from_user_id,
                    'action' => 'reply_sent'
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Reply sent successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Error sending reply', [
                'message_id' => $id,
                'manager_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error sending reply'
            ], 500);
        }
    }

    /**
     * Delete a message
     */
    public function deleteMessage($id)
    {
        try {
            $message = ManagerMessage::forManager(Auth::id())->findOrFail($id);
            $message->delete();

            return response()->json([
                'success' => true,
                'message' => 'Message deleted successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Error deleting message', [
                'message_id' => $id,
                'manager_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error deleting message'
            ], 500);
        }
    }

    /**
     * Get message statistics
     */
    public function getMessageStats()
    {
        try {
            $stats = [
                'total' => ManagerMessage::forManager(Auth::id())->count(),
                'unread' => ManagerMessage::forManager(Auth::id())->unread()->count(),
                'by_type' => [
                    'system' => ManagerMessage::forManager(Auth::id())->byType('system')->count(),
                    'customer_inquiry' => ManagerMessage::forManager(Auth::id())->byType('customer_inquiry')->count(),
                    'booking_update' => ManagerMessage::forManager(Auth::id())->byType('booking_update')->count(),
                    'payment_notification' => ManagerMessage::forManager(Auth::id())->byType('payment_notification')->count(),
                    'visit_request' => ManagerMessage::forManager(Auth::id())->byType('visit_request')->count(),
                ],
                'by_priority' => [
                    'urgent' => ManagerMessage::forManager(Auth::id())->byPriority('urgent')->count(),
                    'high' => ManagerMessage::forManager(Auth::id())->byPriority('high')->count(),
                    'normal' => ManagerMessage::forManager(Auth::id())->byPriority('normal')->count(),
                    'low' => ManagerMessage::forManager(Auth::id())->byPriority('low')->count(),
                ]
            ];

            return response()->json([
                'success' => true,
                'stats' => $stats
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting message stats', [
                'manager_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error loading message statistics'
            ], 500);
        }
    }

    /**
     * Show all bookings page with data
     */
    public function allBookings()
    {
        $bookings = Booking::with(['user', 'hall', 'package'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $stats = [
            'total_bookings' => Booking::count(),
            'pending_bookings' => Booking::where('visit_submitted', true)->where('visit_confirmed', false)->count(),
            'confirmed_bookings' => Booking::where('advance_payment_paid', true)->count(),
            'revenue_this_month' => Booking::where('advance_payment_paid', true)
                ->whereMonth('created_at', now()->month)
                ->sum('advance_payment_amount'),
        ];

        return view('manager.bookings', compact('bookings', 'stats'));
    }

    /**
     * Show calendar page with events
     */
    public function calendar()
    {
        // Get upcoming visits and weddings for calendar view
        $visits = Booking::where('visit_submitted', true)
            ->where('visit_date', '>=', now())
            ->with(['user', 'hall'])
            ->orderBy('visit_date')
            ->get();

        $weddings = Booking::where('advance_payment_paid', true)
            ->where('event_date', '>=', now())
            ->with(['user', 'hall'])
            ->orderBy('event_date')
            ->get();

        return view('manager.calendar', compact('visits', 'weddings'));
    }

    /**
     * Show messages page with data
     */
    public function messagesPage()
    {
        // For now, return a simple view. In a full implementation, you'd load actual messages
        $messages = collect(); // Empty collection for now
        $stats = [
            'total' => 0,
            'unread' => 0,
            'urgent' => 0,
        ];

        return view('manager.messages', compact('messages', 'stats'));
    }
}
