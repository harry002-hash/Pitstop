<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KendaraanController extends Controller
{
    // Form ubah data motor milik customer yang sedang login.
    public function edit(): View
    {
        $vehicle = auth()->user()->vehicles()->latest()->firstOrFail();

        return view('edit-kendaraan', compact('vehicle'));
    }

    // Simpan perubahan. Customer hanya boleh ubah plat + nama motor;
    // status tetap wewenang bengkel.
    public function update(Request $request): RedirectResponse
    {
        $vehicle = auth()->user()->vehicles()->latest()->firstOrFail();

        $validated = $request->validate([
            'plate_number' => ['required', 'string', 'max:20', Rule::unique('vehicles')->ignore($vehicle->id)],
            'vehicle_name' => ['required', 'string', 'max:100'],
        ]);

        $vehicle->update($validated);

        return redirect()->route('customer.dashboard')->with('status', 'Data kendaraan berhasil diperbarui!');
    }
}
