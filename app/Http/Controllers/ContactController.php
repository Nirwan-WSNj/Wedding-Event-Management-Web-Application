<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomerMessage;
use App\Models\MessageReply;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
    /**
     * Show the contact form
     */
    public function show()
    {
        return view('ContactUs');
    }

    /**
     * Handle contact form submission
     */
    public function submit(Request $request)
    {
        try {
            // Validate the form data
            $validated = $request->validate([
                'firstName' => 'required|string|max:255',
                'lastName' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'required|string|max:20',
                'eventType' => 'required|string|in:wedding,engagement,rehearsal,reception,other',
                'date' => 'nullable|date|after:today',
                'guestCount' => 'nullable|integer|min:1|max:1000',
                'message' => 'required|string|max:2000'
            ]);

            DB::beginTransaction();

            // Create or find the customer user
            $customerEmail = $validated['email'];
            $customerName = $validated['firstName'] . ' ' . $validated['lastName'];
            
            $customer = User::where('email', $customerEmail)->first();
            
            if (!$customer) {
                // Create a new customer user
                $customer = User::create([
                    'id' => 'CUS' . str_pad(User::where('role', 'customer')->count() + 1, 4, '0', STR_PAD_LEFT),
                    'name' => $customerName,
                    'email' => $customerEmail,
                    'role' => 'customer',
                    'password' => bcrypt('temporary_password_' . time()), // Temporary password
                    'phone' => $validated['phone']
                ]);
            }

            // Create the customer message using the new model
            $customerMessage = CustomerMessage::createFromContactForm($validated, $customer->id);

            // Log the contact form submission
            Log::info('Contact form submitted successfully', [
                'customer_name' => $customerName,
                'customer_email' => $customerEmail,
                'event_type' => $validated['eventType'],
                'message_id' => $customerMessage->id,
                'priority' => $customerMessage->priority
            ]);

            DB::commit();

            // Return success response
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Thank you for your inquiry! Our wedding team will contact you within 24 hours.',
                    'message_id' => $customerMessage->id
                ]);
            }

            return back()->with('success', 'Thank you for your inquiry! Our wedding team will contact you within 24 hours.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please check your form data and try again.',
                    'errors' => $e->errors()
                ], 422);
            }

            return back()->withErrors($e->errors())->withInput();

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Contact form submission failed', [
                'error' => $e->getMessage(),
                'request_data' => $request->all()
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sorry, there was an error processing your request. Please try again or call us directly.'
                ], 500);
            }

            return back()->with('error', 'Sorry, there was an error processing your request. Please try again or call us directly.')->withInput();
        }
    }

    /**
     * Get all customer messages for manager dashboard
     */
    public function getMessages(Request $request)
    {
        try {
            $query = CustomerMessage::with(['user', 'replies'])
                ->orderBy('created_at', 'desc');

            // Apply filters
            if ($request->has('status') && $request->status !== '') {
                $query->where('status', $request->status);
            }

            if ($request->has('priority') && $request->priority !== '') {
                $query->where('priority', $request->priority);
            }

            if ($request->has('type') && $request->type !== '') {
                $query->where('type', $request->type);
            }

            if ($request->has('unread_only') && $request->unread_only) {
                $query->where('is_read', false);
            }

            $messages = $query->paginate(20);

            return response()->json([
                'success' => true,
                'messages' => $messages->items(),
                'pagination' => [
                    'current_page' => $messages->currentPage(),
                    'last_page' => $messages->lastPage(),
                    'total' => $messages->total(),
                    'per_page' => $messages->perPage()
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching customer messages', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error loading messages'
            ], 500);
        }
    }

    /**
     * Reply to a customer message
     */
    public function replyToMessage(Request $request, $messageId)
    {
        try {
            $request->validate([
                'reply_content' => 'required|string|max:2000'
            ]);

            $message = CustomerMessage::findOrFail($messageId);
            $managerName = Auth::user()->name ?? 'Wet Water Resort Team';

            // Add the reply
            $reply = $message->addReply(
                $request->reply_content,
                true, // is from manager
                $managerName
            );

            // Mark message as read
            $message->markAsRead();

            Log::info('Manager replied to customer message', [
                'message_id' => $messageId,
                'manager' => $managerName,
                'customer_email' => $message->customer_email
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Reply sent successfully!',
                'reply' => $reply
            ]);

        } catch (\Exception $e) {
            Log::error('Error replying to customer message', [
                'message_id' => $messageId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error sending reply'
            ], 500);
        }
    }

    /**
     * Mark message as read
     */
    public function markAsRead($messageId)
    {
        try {
            $message = CustomerMessage::findOrFail($messageId);
            $message->markAsRead();

            return response()->json([
                'success' => true,
                'message' => 'Message marked as read'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating message'
            ], 500);
        }
    }

    /**
     * Update message status
     */
    public function updateStatus(Request $request, $messageId)
    {
        try {
            $request->validate([
                'status' => 'required|in:new,in_progress,replied,resolved,closed'
            ]);

            $message = CustomerMessage::findOrFail($messageId);
            $message->update(['status' => $request->status]);

            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating status'
            ], 500);
        }
    }

    /**
     * Get message details with replies
     */
    public function getMessageDetails($messageId)
    {
        try {
            $message = CustomerMessage::with(['user', 'replies' => function($query) {
                $query->orderBy('sent_at', 'asc');
            }])->findOrFail($messageId);

            return response()->json([
                'success' => true,
                'message' => $message
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Message not found'
            ], 404);
        }
    }

    /**
     * Get contact form statistics for admin
     */
    public function getStats()
    {
        try {
            $stats = [
                'total_messages' => CustomerMessage::count(),
                'unread_messages' => CustomerMessage::where('is_read', false)->count(),
                'this_month' => CustomerMessage::whereMonth('created_at', now()->month)->count(),
                'by_type' => CustomerMessage::select('type', DB::raw('count(*) as count'))
                    ->groupBy('type')
                    ->pluck('count', 'type'),
                'by_priority' => CustomerMessage::select('priority', DB::raw('count(*) as count'))
                    ->groupBy('priority')
                    ->pluck('count', 'priority'),
                'by_status' => CustomerMessage::select('status', DB::raw('count(*) as count'))
                    ->groupBy('status')
                    ->pluck('count', 'status'),
                'response_rate' => $this->calculateResponseRate(),
                'avg_response_time' => $this->calculateAverageResponseTime()
            ];

            return response()->json([
                'success' => true,
                'stats' => $stats
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting contact form stats', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error loading statistics'
            ], 500);
        }
    }

    /**
     * Calculate response rate for contact form inquiries
     */
    private function calculateResponseRate(): float
    {
        $totalMessages = CustomerMessage::count();

        if ($totalMessages === 0) {
            return 0;
        }

        $repliedMessages = CustomerMessage::whereIn('status', ['replied', 'resolved', 'closed'])->count();

        return round(($repliedMessages / $totalMessages) * 100, 1);
    }

    /**
     * Calculate average response time in hours
     */
    private function calculateAverageResponseTime(): float
    {
        $repliedMessages = CustomerMessage::whereNotNull('replied_at')->get();

        if ($repliedMessages->isEmpty()) {
            return 0;
        }

        $totalHours = 0;
        foreach ($repliedMessages as $message) {
            $hours = $message->created_at->diffInHours($message->replied_at);
            $totalHours += $hours;
        }

        return round($totalHours / $repliedMessages->count(), 1);
    }

    /**
     * Delete a message (soft delete)
     */
    public function deleteMessage($messageId)
    {
        try {
            $message = CustomerMessage::findOrFail($messageId);
            $message->delete();

            return response()->json([
                'success' => true,
                'message' => 'Message deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting message'
            ], 500);
        }
    }

    /**
     * Bulk mark messages as read
     */
    public function bulkMarkAsRead(Request $request)
    {
        try {
            $request->validate([
                'message_ids' => 'required|array',
                'message_ids.*' => 'integer|exists:customer_messages,id'
            ]);

            CustomerMessage::whereIn('id', $request->message_ids)
                ->update(['is_read' => true]);

            return response()->json([
                'success' => true,
                'message' => 'Messages marked as read'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating messages'
            ], 500);
        }
    }
}