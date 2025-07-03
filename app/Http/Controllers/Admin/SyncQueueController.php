<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SyncQueue;
use App\Services\SyncProcessorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;

class SyncQueueController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    /**
     * Display sync queue dashboard
     */
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');
        $priority = $request->get('priority', 'all');
        $type = $request->get('type', 'all');
        $perPage = $request->get('per_page', 25);

        // Build query
        $query = SyncQueue::query();

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        if ($priority !== 'all') {
            $query->where('priority', $priority);
        }

        if ($type !== 'all') {
            $query->where('sync_type', $type);
        }

        // Get items with pagination
        $items = $query->orderByRaw("
                CASE priority 
                    WHEN 'critical' THEN 4 
                    WHEN 'high' THEN 3 
                    WHEN 'medium' THEN 2 
                    WHEN 'low' THEN 1 
                    ELSE 0 
                END DESC
            ")
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        // Get statistics
        $stats = Cache::remember('sync_queue_stats', 300, function () {
            return SyncQueue::getStatistics();
        });

        // Get filter options
        $filterOptions = [
            'statuses' => SyncQueue::distinct('status')->pluck('status'),
            'priorities' => SyncQueue::distinct('priority')->pluck('priority'),
            'types' => SyncQueue::distinct('sync_type')->pluck('sync_type')
        ];

        return view('admin.sync-queue.index', compact(
            'items', 
            'stats', 
            'filterOptions',
            'status',
            'priority', 
            'type',
            'perPage'
        ));
    }

    /**
     * Show sync queue item details
     */
    public function show(SyncQueue $syncQueue)
    {
        return view('admin.sync-queue.show', compact('syncQueue'));
    }

    /**
     * Manually process a sync queue item
     */
    public function process(SyncQueue $syncQueue, SyncProcessorService $processor)
    {
        try {
            if ($syncQueue->status === 'processing') {
                return response()->json([
                    'success' => false,
                    'message' => 'Item is already being processed'
                ], 400);
            }

            if ($syncQueue->status === 'completed') {
                return response()->json([
                    'success' => false,
                    'message' => 'Item is already completed'
                ], 400);
            }

            // Mark as processing
            $syncQueue->markAsProcessing();

            // Process the item
            $result = $processor->process($syncQueue);

            if ($result) {
                $syncQueue->markAsCompleted();
                return response()->json([
                    'success' => true,
                    'message' => 'Item processed successfully'
                ]);
            } else {
                $syncQueue->markAsFailed('Manual processing returned false');
                return response()->json([
                    'success' => false,
                    'message' => 'Processing failed'
                ], 500);
            }

        } catch (\Exception $e) {
            $syncQueue->markAsFailed($e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Processing failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Retry a failed sync queue item
     */
    public function retry(SyncQueue $syncQueue)
    {
        if (!$syncQueue->canRetry()) {
            return response()->json([
                'success' => false,
                'message' => 'Item cannot be retried (max retries exceeded or not failed)'
            ], 400);
        }

        $syncQueue->update([
            'status' => 'pending',
            'error_message' => null,
            'processed_at' => null
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Item queued for retry'
        ]);
    }

    /**
     * Delete a sync queue item
     */
    public function destroy(SyncQueue $syncQueue)
    {
        if ($syncQueue->status === 'processing') {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete item that is currently being processed'
            ], 400);
        }

        $syncQueue->delete();

        return response()->json([
            'success' => true,
            'message' => 'Item deleted successfully'
        ]);
    }

    /**
     * Bulk actions on sync queue items
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:retry,delete,reset',
            'items' => 'required|array',
            'items.*' => 'exists:sync_queue,id'
        ]);

        $action = $request->get('action');
        $itemIds = $request->get('items');
        $items = SyncQueue::whereIn('id', $itemIds)->get();

        $processed = 0;
        $errors = [];

        foreach ($items as $item) {
            try {
                switch ($action) {
                    case 'retry':
                        if ($item->canRetry()) {
                            $item->update([
                                'status' => 'pending',
                                'error_message' => null,
                                'processed_at' => null
                            ]);
                            $processed++;
                        } else {
                            $errors[] = "Item #{$item->id} cannot be retried";
                        }
                        break;

                    case 'delete':
                        if ($item->status !== 'processing') {
                            $item->delete();
                            $processed++;
                        } else {
                            $errors[] = "Item #{$item->id} is currently processing";
                        }
                        break;

                    case 'reset':
                        if ($item->status === 'processing') {
                            $item->update([
                                'status' => 'pending',
                                'processed_at' => null
                            ]);
                            $processed++;
                        } else {
                            $errors[] = "Item #{$item->id} is not in processing state";
                        }
                        break;
                }
            } catch (\Exception $e) {
                $errors[] = "Error processing item #{$item->id}: " . $e->getMessage();
            }
        }

        return response()->json([
            'success' => true,
            'processed' => $processed,
            'errors' => $errors,
            'message' => "Processed {$processed} items" . (count($errors) > 0 ? " with " . count($errors) . " errors" : "")
        ]);
    }

    /**
     * Run sync queue processing command
     */
    public function runProcessor(Request $request)
    {
        $limit = $request->get('limit', 10);
        
        try {
            Artisan::call('sync:process', [
                '--limit' => $limit
            ]);

            $output = Artisan::output();

            return response()->json([
                'success' => true,
                'message' => 'Sync processor executed successfully',
                'output' => $output
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to run sync processor: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Clean up old sync queue items
     */
    public function cleanup(Request $request)
    {
        $days = $request->get('days', 7);
        
        try {
            Artisan::call('sync:cleanup', [
                '--days' => $days
            ]);

            $output = Artisan::output();

            return response()->json([
                'success' => true,
                'message' => 'Cleanup executed successfully',
                'output' => $output
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to run cleanup: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reset stuck items
     */
    public function resetStuck(Request $request)
    {
        $minutes = $request->get('minutes', 30);
        
        try {
            Artisan::call('sync:reset-stuck', [
                '--minutes' => $minutes
            ]);

            $output = Artisan::output();

            return response()->json([
                'success' => true,
                'message' => 'Stuck items reset successfully',
                'output' => $output
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to reset stuck items: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get real-time statistics
     */
    public function stats()
    {
        $stats = SyncQueue::getStatistics();
        
        // Add queue health calculation
        $stats['queue_health'] = $this->calculateQueueHealth($stats);
        
        return response()->json($stats);
    }

    /**
     * Create a test sync queue item
     */
    public function createTest(Request $request)
    {
        $request->validate([
            'sync_type' => 'required|string',
            'priority' => 'required|in:low,medium,high,critical',
            'test_data' => 'nullable|array'
        ]);

        $item = SyncQueue::queue(
            $request->get('sync_type'),
            $request->get('test_data', ['test' => true, 'created_by' => auth()->id()]),
            $request->get('priority')
        );

        return response()->json([
            'success' => true,
            'message' => 'Test sync item created',
            'item_id' => $item->id
        ]);
    }

    /**
     * Calculate queue health score
     */
    private function calculateQueueHealth(array $stats)
    {
        $score = 100;
        $warnings = [];
        
        // Deduct points for failed items
        if ($stats['failed'] > 0) {
            $failureRate = ($stats['failed'] / max($stats['total'], 1)) * 100;
            if ($failureRate > 10) {
                $score -= 30;
                $warnings[] = "High failure rate: {$failureRate}%";
            } elseif ($failureRate > 5) {
                $score -= 15;
                $warnings[] = "Moderate failure rate: {$failureRate}%";
            }
        }
        
        // Deduct points for stuck processing items
        if ($stats['processing'] > 10) {
            $score -= 20;
            $warnings[] = "Many items stuck in processing: {$stats['processing']}";
        }
        
        // Deduct points for large pending queue
        if ($stats['pending'] > 100) {
            $score -= 25;
            $warnings[] = "Large pending queue: {$stats['pending']} items";
        } elseif ($stats['pending'] > 50) {
            $score -= 10;
            $warnings[] = "Moderate pending queue: {$stats['pending']} items";
        }
        
        // Determine status
        $status = 'Excellent';
        if ($score < 90) $status = 'Good';
        if ($score < 70) $status = 'Fair';
        if ($score < 50) $status = 'Poor';
        if ($score < 30) $status = 'Critical';
        
        return [
            'score' => max(0, $score),
            'status' => $status,
            'warnings' => $warnings
        ];
    }
}