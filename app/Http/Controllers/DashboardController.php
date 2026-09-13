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

        if ($user->isAdmin() || $user->isOwner()) {
            return redirect()->route('owner.dashboard');
        }

        if (! $user->vehicles()->exists()) {
            return redirect()->route('vehicle.claim');
        }

        return redirect()->route('customer.dashboard');
    }
}
