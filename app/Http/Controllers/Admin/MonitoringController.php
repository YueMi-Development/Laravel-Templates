<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Shared\MonitoringController as BaseMonitoringController;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Inertia\Inertia;
use Inertia\Response;

class MonitoringController extends BaseMonitoringController
{
    public function index(Request $request): Response
    {
        return Inertia::render('Admin/Monitoring/Index', $this->getData());
    }

    public function restartWorkers(): RedirectResponse
    {
        try {
            Artisan::call('queue:restart');

            return redirect()->back()->with('success', 'Queue workers signaled to restart successfully.');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Failed to restart queue workers: ' . $e->getMessage());
        }
    }
}
