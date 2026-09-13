<?php

declare(strict_types=1);

namespace App\Services;

use App\Jobs\System\CheckQueueHeartbeatJob;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

final class MonitoringService
{
    private const int HEARTBEAT_THRESHOLD_SECONDS = 120;

    /**
     * Get the monitoring status of the scheduler and queue workers.
     *
     * @return array{
     *     scheduler: array{
     *         name: string,
     *         status: string,
     *         is_running: bool,
     *         last_heartbeat: string|null,
     *         last_heartbeat_ago: string|null,
     *         message: string
     *     },
     *     queue: array{
     *         name: string,
     *         status: string,
     *         is_running: bool,
     *         last_heartbeat: string|null,
     *         last_heartbeat_ago: string|null,
     *         message: string
     *     }
     * }
     */
    public function getHealthStatus(): array
    {
        $schedulerTimestamp = Cache::get('monitor:scheduler:heartbeat');
        $queueTimestamp = Cache::get('monitor:queue:heartbeat');

        $isSchedulerRunning = false;
        $schedulerLastHeartbeat = null;
        $schedulerLastHeartbeatAgo = null;

        if (is_numeric($schedulerTimestamp)) {
            $diff = now()->timestamp - (int) $schedulerTimestamp;
            $isSchedulerRunning = $diff <= self::HEARTBEAT_THRESHOLD_SECONDS;
            $schedulerCarbon = Carbon::createFromTimestamp((int) $schedulerTimestamp);
            $schedulerLastHeartbeat = $schedulerCarbon->toIso8601String();
            $schedulerLastHeartbeatAgo = $schedulerCarbon->diffForHumans();
        }

        $isQueueRunning = false;
        $queueLastHeartbeat = null;
        $queueLastHeartbeatAgo = null;

        if (is_numeric($queueTimestamp)) {
            $diff = now()->timestamp - (int) $queueTimestamp;
            $isQueueRunning = $diff <= self::HEARTBEAT_THRESHOLD_SECONDS;
            $queueCarbon = Carbon::createFromTimestamp((int) $queueTimestamp);
            $queueLastHeartbeat = $queueCarbon->toIso8601String();
            $queueLastHeartbeatAgo = $queueCarbon->diffForHumans();
        }

        return [
            'scheduler' => [
                'name' => 'Laravel Scheduler',
                'status' => $isSchedulerRunning ? 'RUNNING' : 'STOPPED',
                'is_running' => $isSchedulerRunning,
                'last_heartbeat' => $schedulerLastHeartbeat,
                'last_heartbeat_ago' => $schedulerLastHeartbeatAgo,
                'message' => $isSchedulerRunning
                    ? 'The scheduler is correctly heartbeating every minute.'
                    : 'The scheduler is not heartbeating. Make sure your system cron runs "php artisan schedule:run" every minute.',
            ],
            'queue' => [
                'name' => 'Queue Worker',
                'status' => $isQueueRunning ? 'WORKING' : 'STOPPED',
                'is_running' => $isQueueRunning,
                'last_heartbeat' => $queueLastHeartbeat,
                'last_heartbeat_ago' => $queueLastHeartbeatAgo,
                'message' => $isQueueRunning
                    ? 'Queue workers are actively executing background jobs.'
                    : 'Queue workers are not responding or inactive. Start your worker with "php artisan queue:work".',
            ],
        ];
    }

    /**
     * Dispatch an immediate queue worker heartbeat check.
     */
    public function dispatchHeartbeatCheck(): void
    {
        $lastDispatch = Cache::get('monitor:queue:last_dispatch_attempt');
        if (!is_numeric($lastDispatch) || (now()->timestamp - (int) $lastDispatch) > 15) {
            Cache::put('monitor:queue:last_dispatch_attempt', now()->timestamp, 60);
            CheckQueueHeartbeatJob::dispatch();
        }
    }
}
