<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->isAdmin()) {
            return $this->admin();
        }

        if ($user->isStaff()) {
            return $this->staff();
        }

        return $this->user();
    }

    public function admin()
    {
        $user = auth()->user();
        return Inertia::render('Admin/Dashboard', [
            'user' => $user->only('id', 'name', 'email'),
        ]);
    }

    public function staff()
    {
        $user = auth()->user();
        return Inertia::render('Staff/Dashboard', [
            'user' => $user->only('id', 'name', 'email'),
        ]);
    }

    public function user()
    {
        $user = auth()->user();
        return Inertia::render('User/Dashboard', [
            'user' => $user->only('id', 'name', 'email'),
        ]);
    }
}
