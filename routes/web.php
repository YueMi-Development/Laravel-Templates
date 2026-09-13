<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\MonitoringController as AdminMonitoring;
use App\Http\Controllers\Staff\MonitoringController as StaffMonitoring;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::controller(DashboardController::class)->prefix('dashboard')->group(function () {
        Route::get('/', 'index')->name('dashboard');
        Route::get('/admin', 'admin')->middleware('role:admin')->name('dashboard.admin');
        Route::get('/staff', 'staff')->middleware('role:staff,admin')->name('dashboard.staff');
        Route::get('/user', 'user')->name('dashboard.user');
    });

    // Admin routes
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::controller(UserController::class)->prefix('users')->name('users.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('/{user}', 'show')->name('show');
            Route::get('/{user}/edit', 'edit')->name('edit');
            Route::patch('/{user}', 'update')->name('update');
            Route::delete('/{user}', 'destroy')->name('destroy');
        });

        Route::controller(AdminMonitoring::class)->prefix('monitoring')->name('monitoring.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/restart', 'restartWorkers')->name('restart');
            Route::post('/flush', 'flushFailed')->name('flush');
        });
    });

    // Staff routes (includes admin)
    Route::middleware('role:staff,admin')->prefix('staff')->name('staff.')->group(function () {
        Route::controller(StaffMonitoring::class)->prefix('monitoring')->name('monitoring.')->group(function () {
            Route::get('/', 'index')->name('index');
        });
    });
});

Route::middleware('auth')->group(function () {
    Route::controller(ProfileController::class)->prefix('profile')->name('profile.')->group(function () {
        Route::get('/', 'edit')->name('edit');
        Route::patch('/', 'update')->name('update');
        Route::delete('/', 'destroy')->name('destroy');
    });
});

require __DIR__.'/auth.php';
