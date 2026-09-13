<?php

declare(strict_types=1);

namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use App\Services\MonitoringService;

class MonitoringController extends Controller
{
    public function __construct(
        protected readonly MonitoringService $monitoringService
    ) {}

    /**
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
    public function getData(): array
    {
        $this->monitoringService->dispatchHeartbeatCheck();

        return $this->monitoringService->getHealthStatus();
    }
}
