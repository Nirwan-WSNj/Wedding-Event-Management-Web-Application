<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SyncQueue;
use Illuminate\Support\Facades\Cache;

class SyncQueueStats extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:stats 
                            {--cache : Cache the statistics for dashboard display}
                            {--json : Output statistics as JSON}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Display sync queue statistics';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $cache = $this->option('cache');
        $json = $this->option('json');
        
        $this->info('Generating sync queue statistics...');
        
        $stats = SyncQueue::getStatistics();
        
        // Add additional metrics
        $stats['queue_health'] = $this->calculateQueueHealth($stats);
        $stats['performance_metrics'] = $this->getPerformanceMetrics();
        $stats['generated_at'] = now()->toISOString();
        
        if ($cache) {
            Cache::put('sync_queue_stats', $stats, now()->addHour());
            $this->info('Statistics cached for 1 hour');
        }
        
        if ($json) {
            $this->line(json_encode($stats, JSON_PRETTY_PRINT));
        } else {
            $this->displayStats($stats);
        }
        
        return 0;
    }
    
    /**
     * Display statistics in a formatted table
     */
    private function displayStats(array $stats)
    {
        $this->info('=== Sync Queue Statistics ===');
        $this->newLine();
        
        // Overall counts
        $this->info('Overall Status:');
        $this->table(
            ['Status', 'Count'],
            [
                ['Total', $stats['total']],
                ['Pending', $stats['pending']],
                ['Processing', $stats['processing']],
                ['Completed', $stats['completed']],
                ['Failed', $stats['failed']],
                ['Retryable', $stats['retryable']],
            ]
        );
        
        // Priority breakdown
        if (!empty($stats['by_priority'])) {
            $this->newLine();
            $this->info('By Priority:');
            $priorityData = [];
            foreach ($stats['by_priority'] as $priority => $count) {
                $priorityData[] = [ucfirst($priority), $count];
            }
            $this->table(['Priority', 'Count'], $priorityData);
        }
        
        // Type breakdown
        if (!empty($stats['by_type'])) {
            $this->newLine();
            $this->info('By Type:');
            $typeData = [];
            foreach ($stats['by_type'] as $type => $count) {
                $typeData[] = [$type, $count];
            }
            $this->table(['Sync Type', 'Count'], $typeData);
        }
        
        // Performance metrics
        $this->newLine();
        $this->info('Performance:');
        $avgTime = $stats['avg_processing_time'] ? round($stats['avg_processing_time'], 2) . 's' : 'N/A';
        $this->table(
            ['Metric', 'Value'],
            [
                ['Average Processing Time', $avgTime],
                ['Queue Health Score', $stats['queue_health']['score'] . '%'],
                ['Health Status', $stats['queue_health']['status']],
            ]
        );
        
        // Health warnings
        if (!empty($stats['queue_health']['warnings'])) {
            $this->newLine();
            $this->warn('Health Warnings:');
            foreach ($stats['queue_health']['warnings'] as $warning) {
                $this->line("⚠️  {$warning}");
            }
        }
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
    
    /**
     * Get performance metrics
     */
    private function getPerformanceMetrics()
    {
        // Get recent processing times
        $recentItems = SyncQueue::where('status', 'completed')
            ->where('processed_at', '>=', now()->subHour())
            ->whereNotNull('processed_at')
            ->selectRaw('TIMESTAMPDIFF(SECOND, created_at, processed_at) as processing_time')
            ->pluck('processing_time');
        
        if ($recentItems->isEmpty()) {
            return [
                'recent_throughput' => 0,
                'recent_avg_time' => null,
                'recent_max_time' => null,
                'recent_min_time' => null
            ];
        }
        
        return [
            'recent_throughput' => $recentItems->count(),
            'recent_avg_time' => round($recentItems->avg(), 2),
            'recent_max_time' => $recentItems->max(),
            'recent_min_time' => $recentItems->min()
        ];
    }
}