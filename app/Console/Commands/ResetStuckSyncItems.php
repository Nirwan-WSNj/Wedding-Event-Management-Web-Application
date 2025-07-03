<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SyncQueue;
use Illuminate\Support\Facades\Log;

class ResetStuckSyncItems extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:reset-stuck 
                            {--minutes=30 : Items stuck for this many minutes will be reset}
                            {--dry-run : Show what would be reset without actually resetting}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset sync queue items that are stuck in processing state';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $minutes = (int) $this->option('minutes');
        $dryRun = $this->option('dry-run');
        
        $this->info("Looking for items stuck in processing for more than {$minutes} minutes");
        
        if ($dryRun) {
            $this->warn('DRY RUN MODE - No items will actually be reset');
        }
        
        // Find stuck items
        $stuckItems = SyncQueue::where('status', 'processing')
            ->where('processed_at', '<', now()->subMinutes($minutes))
            ->get();
        
        if ($stuckItems->isEmpty()) {
            $this->info('No stuck items found');
            return 0;
        }
        
        $this->info("Found {$stuckItems->count()} stuck items:");
        
        foreach ($stuckItems as $item) {
            $stuckFor = now()->diffInMinutes($item->processed_at);
            $this->line("- Item #{$item->id} ({$item->sync_type}) stuck for {$stuckFor} minutes");
        }
        
        if (!$dryRun) {
            if (!$this->confirm('Do you want to reset these stuck items?')) {
                $this->info('Reset cancelled');
                return 0;
            }
            
            $resetCount = SyncQueue::resetStuckItems($minutes);
            $this->info("Reset {$resetCount} stuck items back to pending status");
            
            // Log the reset action
            foreach ($stuckItems as $item) {
                Log::warning('Reset stuck sync queue item', [
                    'item_id' => $item->id,
                    'sync_type' => $item->sync_type,
                    'stuck_for_minutes' => now()->diffInMinutes($item->processed_at)
                ]);
            }
        } else {
            $this->info('DRY RUN: Would reset ' . $stuckItems->count() . ' items');
        }
        
        return 0;
    }
}