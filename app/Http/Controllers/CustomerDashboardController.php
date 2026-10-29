<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CustomerDashboardController extends Controller
{
    public function index(Request $request): View
    {
        return view('dashboardUser', ['vehicle' => $request->user()->vehicle]);
    }

    public function status(Request $request): JsonResponse
    {
        $vehicle = $request->user()->vehicle()->firstOrFail();

        return response()->json([
            'id' => $vehicle->id,
            'plate_number' => $vehicle->plate_number,
            'status' => $vehicle->status,
            'status_label' => $vehicle->statusLabel(),
        ]);
    }
}
