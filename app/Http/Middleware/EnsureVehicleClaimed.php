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

        if ($user && ! $user->isOwner() && ! $user->vehicles()->exists()) {
            return redirect()->route('vehicle.claim');
        }

        return $next($request);
    }
}
