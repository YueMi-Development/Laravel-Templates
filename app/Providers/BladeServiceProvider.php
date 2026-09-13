<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class BladeServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $user = auth()->user();

        Blade::if('isAdmin', fn () => $user?->isAdmin());
        Blade::if('isStaff', fn () => $user?->isStaff());
        Blade::if('isStaffOrAbove', fn () => $user?->isStaffOrAbove());
        Blade::if('isUser', fn () => $user?->isUser());
    }
}
