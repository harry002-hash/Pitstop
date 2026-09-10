<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;

class CustomerDashboardController extends Controller
{
    public function index(): View
    {
        $vehicle = auth()->user()->vehicles()->latest()->first();

        return view('customer.dashboard', compact('vehicle'));
    }

    public function status(): JsonResponse
    {
        $vehicle = auth()->user()->vehicles()->latest()->firstOrFail();

        return response()->json([
            'id' => $vehicle->id,
            'status' => $vehicle->status,
            'status_label' => $vehicle->statusLabel(),
        ]);
    }
}
