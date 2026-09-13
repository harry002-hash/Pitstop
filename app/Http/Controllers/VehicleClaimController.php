<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClaimVehicleRequest;
use App\Models\Vehicle;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;

class VehicleClaimController extends Controller
{
    public function create(): View|RedirectResponse
    {
        if (auth()->user()->isAdmin()) {
            return redirect()->route('owner.vehicles.index');
        }

        if (auth()->user()->isOwner()) {
            return redirect()->route('owner.vehicles.index');
        }

        if (auth()->user()->vehicles()->exists()) {
            return redirect()->route('customer.dashboard');
        }

        return view('vehicles.claim');
    }

    public function store(ClaimVehicleRequest $request): RedirectResponse
    {
        $vehicle = Vehicle::where('plate_number', $request->validated('plate_number'))->first();

        if (! $vehicle || ! Hash::check($request->validated('plate_password'), $vehicle->plate_password)) {
            return back()->withErrors(['plate_number' => 'Nomor KB atau password salah.'])->onlyInput('plate_number');
        }

        if ($vehicle->isClaimed() && $vehicle->user_id !== auth()->id()) {
            return back()->withErrors(['plate_number' => 'Motor ini sudah diklaim akun lain.'])->onlyInput('plate_number');
        }

        $vehicle->update(['user_id' => auth()->id()]);

        return redirect()->route('customer.dashboard')->with('status', 'Motor berhasil ditautkan.');
    }
}
