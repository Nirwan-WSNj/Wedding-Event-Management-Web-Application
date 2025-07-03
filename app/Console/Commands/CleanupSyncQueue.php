<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SyncQueue;

class CleanupSyncQueue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:cleanup 
                            {--days=7 : Number of days to keep completed items}
                            {--dry-run : Show what would be deleted without actually deleting}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up old completed items from the sync queue';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = (int) $this->option('days');
        $dryRun = $this->option('dry-run');
        
        $this->info("Cleaning up sync queue items older than {$days} days");
        
        if ($dryRun) {
            $this->warn('DRY RUN MODE - No items will actually be deleted');
        }
        
        // Count items to be deleted
        $completedCount = SyncQueue::where('status', 'completed')
            ->where('processed_at', '<', now()->subDays($days))
            ->count();
            
        $failedCount = SyncQueue::where('status', 'failed')
            ->where('retry_count', '>=', SyncQueue::raw('max_retries'))
            ->where('updated_at', '<', now()->subDays($days * 2)) // Keep failed items longer
            ->count();
        
        $this->info("Found {$completedCount} completed items to clean up");
        $this->info("Found {$failedCount} permanently failed items to clean up");
        
        if ($completedCount === 0 && $failedCount === 0) {
            $this->info('No items to clean up');
            return 0;
        }
        
        if (!$dryRun) {
            if (!$this->confirm('Do you want to proceed with cleanup?')) {
                $this->info('Cleanup cancelled');
                return 0;
            }
            
            // Delete completed items
            $deletedCompleted = SyncQueue::where('status', 'completed')
                ->where('processed_at', '<', now()->subDays($days))
                ->delete();
                
            // Delete permanently failed items
            $deletedFailed = SyncQueue::where('status', 'failed')
                ->where('retry_count', '>=', SyncQueue::raw('max_retries'))
                ->where('updated_at', '<', now()->subDays($days * 2))
                ->delete();
            
            $this->info("Deleted {$deletedCompleted} completed items");
            $this->info("Deleted {$deletedFailed} permanently failed items");
            $this->info('Cleanup completed successfully');
        } else {
            $this->info('DRY RUN: Would delete ' . ($completedCount + $failedCount) . ' items');
        }
        
        return 0;
    }
}