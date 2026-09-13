<?php

declare(strict_types=1);

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Shared\MonitoringController as BaseMonitoringController;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class MonitoringController extends BaseMonitoringController
{
    public function index(Request $request): Response
    {
        return Inertia::render('Staff/Monitoring/Index', $this->getData());
    }
}
