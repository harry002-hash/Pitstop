<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;

class DashboardController extends Controller
{
    public function index(): RedirectResponse
    {
        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        if (auth()->user()->isOwner()) {
            return redirect()->route('owner.vehicles.index');
        }

        if (! auth()->user()->vehicles()->exists()) {
            return redirect()->route('vehicle.claim');
        }

        return redirect()->route('customer.dashboard');
    }
}
