<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureVehicleClaimed
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if (! $user) {
            return redirect()->route('login');
        }

        if (! $user->isOwner() && ! $user->isAdmin() && ! $user->vehicles()->exists()) {
            return redirect()->route('vehicle.claim');
        }

        return $next($request);
    }
}
