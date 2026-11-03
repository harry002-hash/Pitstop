<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClaimVehicleRequest;
use App\Models\Vehicle;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class VehicleClaimController extends Controller
{
    public function create(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        if (! $user->shouldClaimVehicle()) {
            return redirect()->route($user->landingRouteName());
        }

        return view('vehicles.claim');
    }

    public function store(ClaimVehicleRequest $request): RedirectResponse
    {
        // Kunci baris supaya dua customer tidak bisa mengklaim motor yang sama
        // secara bersamaan (cek lalu tulis harus atomik).
        $result = DB::transaction(function () use ($request): string {
            $vehicle = Vehicle::where('plate_number', $request->validated('plate_number'))
                ->lockForUpdate()
                ->first();

            if (! $vehicle || ! Hash::check($request->validated('plate_password'), $vehicle->plate_password)) {
                return 'invalid';
            }

            if ($vehicle->isClaimed() && $vehicle->user_id !== $request->user()->id) {
                return 'taken';
            }

            $vehicle->update(['user_id' => $request->user()->id]);

            return 'claimed';
        });

        if ($result !== 'claimed') {
            return back()
                ->withErrors(['plate_number' => $result === 'invalid'
                    ? 'Nomor KB atau password salah.'
                    : 'Motor ini sudah diklaim akun lain.'])
                ->onlyInput('plate_number');
        }

        return redirect()->route('customer.dashboard')->with('status', 'Motor berhasil ditautkan.');
    }
}
