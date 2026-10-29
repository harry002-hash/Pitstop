<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request): RedirectResponse
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        if ($user->isStaff()) {
            return redirect()->route('owner.dashboard');
        }

        if (! $user->hasVehicle()) {
            return redirect()->route('vehicle.claim');
        }

        return redirect()->route('customer.dashboard');
    }
}
