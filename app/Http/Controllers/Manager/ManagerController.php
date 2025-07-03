<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Hall;
use App\Models\Package;
use App\Models\User;
use App\Models\ManagerNotification;
use App\Models\ManagerCallLog;
use App\Models\SystemIntegrationLog;
use App\Services\DeepIntegrationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ManagerController extends Controller
{
    protected $deepIntegrationService;

    public function __construct(DeepIntegrationService $deepIntegrationService)
    {
        $this->deepIntegrationService = $deepIntegrationService;
        $this->middleware('auth');
        $this->middleware('role:manager');
    }

    /**
     * Display manager dashboard with real-time data
     */
    public function dashboard()
    {
        try {
            // Get real-time statistics
            $stats = $this->getDashboardStatistics();
            
            // Get recent activities
            $recentActivities = $this->getRecentActivities();
            
            // Get pending notifications
            $notifications = ManagerNotification::where('manager_id', Auth::id())
                ->unread()
                ->orderBy('priority', 'desc')
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();

            return view('manager.dashboard', compact('stats', 'recentActivities', 'notifications'));
            
        } catch (\Exception $e) {
            Log::error('Manager dashboard error', [
                'manager_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);
            
            return view('manager.dashboard')->with('error', 'Unable to load dashboard data');
        }
    }

    /**
     * Get visit requests for manager approval
     */
    public function getVisitRequests(Request $request)
    {
        try {
            $query = Booking::where('visit_submitted', true)
                ->with(['user', 'hall', 'package']);

            // Apply filters
            if ($request->filled('status')) {
                if ($request->status === 'pending') {
                    $query->where('visit_confirmed', false);
                } elseif ($request->status === 'approved') {
                    $query->where('visit_confirmed', true);
                }
            }

            if ($request->filled('hall')) {
                $query->where('hall_name', $request->hall);
            }

            if ($request->filled('date')) {
                $query->whereDate('visit_date', $request->date);
            }

            $visits = $query->orderBy('created_at', 'desc')->paginate(15);

            if ($request->ajax()) {
                return response()->json([
                    'visits' => $visits,
                    'stats' => $this->getVisitStatistics()
                ]);
            }

            return view('manager.visits.index', compact('visits'));
            
        } catch (\Exception $e) {
            Log::error('Error fetching visit requests', [
                'manager_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);
            
            return response()->json(['error' => 'Unable to fetch visit requests'], 500);
        }
    }

    /**
     * Approve visit request with customer call tracking
     */
    public function approveVisit(Request $request, $bookingId)
    {
        $validated = $request->validate([
            'call_status' => 'required|in:successful,no_answer,busy,invalid_number',
            'call_notes' => 'nullable|string|max:1000',
            'visit_confirmed' => 'required|boolean',
            'new_visit_date' => 'nullable|date|after:today',
            'new_visit_time' => 'nullable|string',
            'manager_notes' => 'nullable|string|max:1000',
            'call_duration' => 'nullable|integer|min:0'
        ]);

        try {
            DB::beginTransaction();

            $booking = Booking::findOrFail($bookingId);
            
            // Record the call attempt
            $callLog = ManagerCallLog::create([
                'booking_id' => $booking->id,
                'manager_id' => Auth::id(),
                'call_status' => $validated['call_status'],
                'call_notes' => $validated['call_notes'],
                'call_attempted_at' => now(),
                'customer_phone' => $booking->contact_phone,
                'customer_name' => $booking->contact_name,
                'call_duration_seconds' => $validated['call_duration'] ?? null,
                'call_outcome' => $validated['visit_confirmed'] ? 'visit_approved' : 'visit_rejected'
            ]);

            // If call was successful and visit is confirmed
            if ($validated['call_status'] === 'successful' && $validated['visit_confirmed']) {
                
                // Update visit date/time if provided
                if ($validated['new_visit_date']) {
                    $booking->visit_date = $validated['new_visit_date'];
                }
                if ($validated['new_visit_time']) {
                    $booking->visit_time = $validated['new_visit_time'];
                }

                // Confirm visit using the new workflow method
                $callData = [
                    'notes' => $validated['manager_notes'],
                    'call_log_id' => $callLog->id,
                    'call_status' => $validated['call_status'],
                    'call_duration' => $validated['call_duration'] ?? null
                ];

                $booking->confirmVisitAfterCall(Auth::user(), $callData);

                // Create payment pending notification
                ManagerNotification::createPaymentPendingNotification(Auth::id(), $booking);

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Visit approved successfully! Customer will be notified.',
                    'booking' => $booking->fresh(),
                    'advance_payment_amount' => $booking->advance_payment_amount
                ]);

            } else {
                // Handle unsuccessful call or rejected visit
                $booking->increment('visit_call_attempts');
                $booking->update([
                    'last_call_attempt_at' => now(),
                    'last_call_status' => $validated['call_status'],
                    'last_call_notes' => $validated['call_notes']
                ]);

                if ($validated['call_status'] === 'successful' && !$validated['visit_confirmed']) {
                    $booking->update([
                        'visit_rejected' => true,
                        'visit_rejected_at' => now(),
                        'visit_rejection_reason' => $validated['manager_notes'] ?? 'Customer declined visit'
                    ]);
                }

                DB::commit();

                $message = $validated['call_status'] === 'successful' 
                    ? 'Customer call completed - visit not confirmed'
                    : 'Call attempt recorded - will retry later';

                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'call_status' => $validated['call_status'],
                    'retry_needed' => in_array($validated['call_status'], ['no_answer', 'busy'])
                ]);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Visit approval failed', [
                'booking_id' => $bookingId,
                'manager_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to process visit approval: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reject visit request
     */
    public function rejectVisit(Request $request, $bookingId)
    {
        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:1000',
            'call_status' => 'nullable|in:successful,no_answer,busy,invalid_number',
            'call_notes' => 'nullable|string|max:1000',
            'call_duration' => 'nullable|integer|min:0'
        ]);

        try {
            DB::beginTransaction();

            $booking = Booking::findOrFail($bookingId);
            
            // Validate that visit was submitted
            if (!$booking->visit_submitted) {
                return response()->json([
                    'success' => false,
                    'message' => 'Visit request has not been submitted yet'
                ], 422);
            }

            // Validate that visit is not already confirmed
            if ($booking->visit_confirmed) {
                return response()->json([
                    'success' => false,
                    'message' => 'Visit has already been confirmed and cannot be rejected'
                ], 422);
            }

            // Record the call attempt if call was made
            if ($validated['call_status']) {
                ManagerCallLog::create([
                    'booking_id' => $booking->id,
                    'manager_id' => Auth::id(),
                    'call_status' => $validated['call_status'],
                    'call_notes' => $validated['call_notes'],
                    'call_attempted_at' => now(),
                    'customer_phone' => $booking->contact_phone,
                    'customer_name' => $booking->contact_name,
                    'call_duration_seconds' => $validated['call_duration'] ?? null,
                    'call_outcome' => 'visit_rejected'
                ]);
            }

            // Update booking with rejection details
            $booking->update([
                'visit_rejected' => true,
                'visit_rejected_at' => now(),
                'visit_rejected_by' => Auth::id(),
                'visit_rejection_reason' => $validated['rejection_reason'],
                'manager_call_completed' => true,
                'manager_call_completed_at' => now(),
                'manager_call_completed_by' => Auth::id(),
                'workflow_step' => 'visit_rejected',
                'workflow_notes' => 'Visit request rejected by manager. Reason: ' . $validated['rejection_reason'],
                'status' => Booking::STATUS_CANCELLED,
                'cancelled_at' => now(),
                'cancellation_reason' => 'Visit request rejected: ' . $validated['rejection_reason']
            ]);

            // Send rejection notification to customer
            if ($booking->user) {
                $booking->user->notify(new \App\Notifications\VisitRejectionNotification($booking, $validated['rejection_reason']));
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Visit request rejected successfully. Customer has been notified.',
                'booking' => $booking->fresh()
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Visit rejection failed', [
                'booking_id' => $bookingId,
                'manager_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to reject visit request: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Confirm payment and unlock Step 5
     */
    public function confirmPayment(Request $request, $bookingId)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,credit_card,bank_transfer,cheque',
            'transaction_id' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000'
        ]);

        try {
            DB::beginTransaction();

            $booking = Booking::findOrFail($bookingId);
            
            // Validate that visit is confirmed
            if (!$booking->visit_confirmed) {
                return response()->json([
                    'success' => false,
                    'message' => 'Visit must be confirmed before payment can be processed'
                ], 422);
            }

            // Validate payment amount
            if ($validated['amount'] != $booking->advance_payment_amount) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment amount does not match required advance payment'
                ], 422);
            }

            // Handle payment confirmation using the new workflow method
            $booking->markAdvancePaymentPaid(
                $validated['payment_method'],
                $validated['notes'],
                $validated // Pass all payment details for email
            );

            // Mark payment notification as read
            ManagerNotification::where('manager_id', Auth::id())
                ->where('notification_type', 'payment_pending')
                ->whereJsonContains('notification_data->booking_id', $booking->id)
                ->update(['is_read' => true, 'read_at' => now()]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Payment confirmed successfully! Customer can now proceed to Step 5.',
                'booking' => $booking->fresh()
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment confirmation failed', [
                'booking_id' => $bookingId,
                'manager_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to confirm payment: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get call history for a booking
     */
    public function getCallHistory($bookingId)
    {
        try {
            $booking = Booking::findOrFail($bookingId);
            
            $callHistory = ManagerCallLog::where('booking_id', $bookingId)
                ->with('manager')
                ->orderBy('call_attempted_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'call_history' => $callHistory,
                'booking_info' => [
                    'id' => $booking->id,
                    'customer_name' => $booking->contact_name,
                    'customer_phone' => $booking->contact_phone,
                    'total_attempts' => $booking->visit_call_attempts ?? 0,
                    'last_attempt' => $booking->last_call_attempt_at,
                    'last_status' => $booking->last_call_status
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching call history', [
                'booking_id' => $bookingId,
                'manager_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error retrieving call history'
            ], 500);
        }
    }

    /**
     * Schedule callback for later
     */
    public function scheduleCallback(Request $request, $bookingId)
    {
        $validated = $request->validate([
            'callback_date' => 'required|date|after_or_equal:today',
            'callback_time' => 'required|string',
            'callback_notes' => 'nullable|string|max:500'
        ]);

        try {
            $booking = Booking::findOrFail($bookingId);
            
            $booking->update([
                'callback_scheduled' => true,
                'callback_date' => $validated['callback_date'],
                'callback_time' => $validated['callback_time'],
                'callback_notes' => $validated['callback_notes'],
                'callback_scheduled_by' => Auth::id(),
                'callback_scheduled_at' => now()
            ]);

            // Create reminder notification
            ManagerNotification::create([
                'manager_id' => Auth::id(),
                'notification_type' => 'callback_reminder',
                'title' => 'Callback Scheduled',
                'message' => "Callback scheduled for {$booking->contact_name} on {$validated['callback_date']} at {$validated['callback_time']}",
                'notification_data' => [
                    'booking_id' => $booking->id,
                    'callback_date' => $validated['callback_date'],
                    'callback_time' => $validated['callback_time'],
                    'customer_name' => $booking->contact_name,
                    'customer_phone' => $booking->contact_phone
                ],
                'priority' => 'medium',
                'is_actionable' => true,
                'action_url' => route('manager.visits.show', $booking->id),
                'expires_at' => Carbon::parse($validated['callback_date'])->addDay()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Callback scheduled successfully',
                'callback_info' => [
                    'date' => $validated['callback_date'],
                    'time' => $validated['callback_time'],
                    'notes' => $validated['callback_notes']
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error scheduling callback', [
                'booking_id' => $bookingId,
                'manager_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error scheduling callback: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get manager notifications
     */
    public function getNotifications(Request $request)
    {
        try {
            $query = ManagerNotification::where('manager_id', Auth::id());

            // Apply filters
            if ($request->filled('type')) {
                $query->where('notification_type', $request->type);
            }

            if ($request->filled('unread_only')) {
                $query->unread();
            }

            if ($request->filled('priority')) {
                $query->where('priority', $request->priority);
            }

            $notifications = $query->active()
                ->orderBy('priority', 'desc')
                ->orderBy('created_at', 'desc')
                ->paginate(20);

            $stats = ManagerNotification::getStatsForManager(Auth::id());

            return response()->json([
                'notifications' => $notifications,
                'stats' => $stats
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching notifications', [
                'manager_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return response()->json(['error' => 'Unable to fetch notifications'], 500);
        }
    }

    /**
     * Mark notification as read
     */
    public function markNotificationRead($notificationId)
    {
        try {
            $notification = ManagerNotification::where('manager_id', Auth::id())
                ->findOrFail($notificationId);
            
            $notification->markAsRead();

            return response()->json([
                'success' => true,
                'message' => 'Notification marked as read'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error marking notification as read'
            ], 500);
        }
    }

    /**
     * Mark all notifications as read
     */
    public function markAllNotificationsRead()
    {
        try {
            $count = ManagerNotification::markAllAsReadForManager(Auth::id());

            return response()->json([
                'success' => true,
                'message' => "Marked {$count} notifications as read"
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error marking notifications as read'
            ], 500);
        }
    }

    /**
     * Get dashboard statistics
     */
    private function getDashboardStatistics()
    {
        return [
            'total_halls' => Hall::where('is_active', true)->count(),
            'pending_visits' => Booking::where('visit_submitted', true)
                ->where('visit_confirmed', false)->count(),
            'confirmed_bookings' => Booking::where('advance_payment_paid', true)->count(),
            'total_revenue' => Booking::where('advance_payment_paid', true)
                ->sum('advance_payment_amount'),
            'bookings_today' => Booking::whereDate('created_at', today())->count(),
            'bookings_this_week' => Booking::whereBetween('created_at', [
                now()->startOfWeek(), now()->endOfWeek()
            ])->count(),
            'bookings_this_month' => Booking::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)->count(),
            'most_booked_hall' => Hall::withCount(['bookings' => function($query) {
                $query->where('advance_payment_paid', true);
            }])->orderBy('bookings_count', 'desc')->first()?->name ?? 'None',
            'average_booking_value' => Booking::where('advance_payment_paid', true)
                ->avg('advance_payment_amount') ?? 0,
            'pending_payments' => Booking::where('visit_confirmed', true)
                ->where('advance_payment_paid', false)->count(),
            'unread_notifications' => ManagerNotification::where('manager_id', Auth::id())
                ->unread()->count()
        ];
    }

    /**
     * Get recent activities
     */
    private function getRecentActivities()
    {
        return [
            'recent_visits' => Booking::where('visit_submitted', true)
                ->where('visit_confirmed', false)
                ->with(['user', 'hall'])
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get(),
            'upcoming_weddings' => Booking::where('advance_payment_paid', true)
                ->where('event_date', '>=', now())
                ->with(['user', 'hall'])
                ->orderBy('event_date', 'asc')
                ->take(5)
                ->get(),
            'payment_confirmations' => Booking::where('visit_confirmed', true)
                ->where('advance_payment_paid', false)
                ->with(['user', 'hall'])
                ->orderBy('visit_confirmed_at', 'desc')
                ->take(5)
                ->get()
        ];
    }

    /**
     * Get visit statistics
     */
    private function getVisitStatistics()
    {
        return [
            'pending_visits' => Booking::where('visit_submitted', true)
                ->where('visit_confirmed', false)->count(),
            'approved_visits' => Booking::where('visit_confirmed', true)->count(),
            'completed_visits' => Booking::where('visit_confirmed', true)
                ->where('advance_payment_paid', true)->count(),
            'conversion_rate' => $this->calculateVisitConversionRate()
        ];
    }

    /**
     * Calculate visit to booking conversion rate
     */
    private function calculateVisitConversionRate()
    {
        $totalVisits = Booking::where('visit_submitted', true)->count();
        $convertedBookings = Booking::where('visit_confirmed', true)
            ->where('advance_payment_paid', true)->count();

        return $totalVisits > 0 ? round(($convertedBookings / $totalVisits) * 100, 1) : 0;
    }

    /**
     * Get integration health status
     */
    public function getIntegrationHealth()
    {
        try {
            $stats = SystemIntegrationLog::getStatistics('24h');
            $recentFailures = SystemIntegrationLog::getRecentFailures(5);

            return response()->json([
                'health_status' => $stats['success_rate'] >= 95 ? 'healthy' : 'warning',
                'statistics' => $stats,
                'recent_failures' => $recentFailures
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'health_status' => 'error',
                'message' => 'Unable to fetch integration health data'
            ], 500);
        }
    }
}