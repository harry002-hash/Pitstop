<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureOwner
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth()->check() || (! auth()->user()->isOwner() && ! auth()->user()->isAdmin())) {
            abort(403, 'Hanya pemilik bengkel.');
        }

        return $next($request);
    }
}
