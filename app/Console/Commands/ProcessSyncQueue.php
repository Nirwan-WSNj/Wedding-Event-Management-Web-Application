<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SyncQueue;
use App\Services\SyncProcessorService;
use Illuminate\Support\Facades\Log;

class ProcessSyncQueue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:process 
                            {--limit=10 : Number of items to process}
                            {--timeout=300 : Maximum execution time in seconds}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process pending items in the sync queue';

    /**
     * Execute the console command.
     */
    public function handle(SyncProcessorService $processor)
    {
        $limit = (int) $this->option('limit');
        $timeout = (int) $this->option('timeout');
        
        $this->info("Starting sync queue processing (limit: {$limit}, timeout: {$timeout}s)");
        
        $startTime = time();
        $processed = 0;
        $failed = 0;
        
        try {
            // Get items to process
            $items = SyncQueue::getNextToProcess($limit);
            
            if ($items->isEmpty()) {
                $this->info('No items to process in sync queue');
                return 0;
            }
            
            $this->info("Found {$items->count()} items to process");
            
            foreach ($items as $item) {
                // Check timeout
                if (time() - $startTime >= $timeout) {
                    $this->warn('Timeout reached, stopping processing');
                    break;
                }
                
                try {
                    $this->line("Processing item #{$item->id} ({$item->sync_type})");
                    
                    // Mark as processing
                    $item->markAsProcessing();
                    
                    // Process the item
                    $result = $processor->process($item);
                    
                    if ($result) {
                        $item->markAsCompleted();
                        $processed++;
                        $this->info("✓ Completed item #{$item->id}");
                    } else {
                        $item->markAsFailed('Processing returned false');
                        $failed++;
                        $this->error("✗ Failed item #{$item->id}");
                    }
                    
                } catch (\Exception $e) {
                    $item->markAsFailed($e->getMessage());
                    $failed++;
                    $this->error("✗ Error processing item #{$item->id}: {$e->getMessage()}");
                    
                    Log::error('Sync queue processing error', [
                        'item_id' => $item->id,
                        'sync_type' => $item->sync_type,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                }
            }
            
            $duration = time() - $startTime;
            $this->info("Processing completed in {$duration}s");
            $this->info("Processed: {$processed}, Failed: {$failed}");
            
            return 0;
            
        } catch (\Exception $e) {
            $this->error("Fatal error in sync queue processing: {$e->getMessage()}");
            Log::error('Fatal sync queue error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return 1;
        }
    }
}