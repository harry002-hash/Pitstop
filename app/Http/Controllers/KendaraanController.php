<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KendaraanController extends Controller
{
    // Method menampilkan form edit (menerima $id dari route /vehicles/{id}/edit)
    public function edit($id)
    {
        $kendaraan = [
            'id'             => $id,
            'license_plate'  => 'KB 8123 XG',
            'owner_name'    => 'Budi Heremanto',
            'vehicle_type' => 'Motor',
            'vehicle_name'  => 'Vario 125 Gen 1',
            'status'          => 'Dikerjakan',
        ];

        return view('edit-kendaraan', compact('kendaraan'));
    }

    // Method proses simpan update (menerima $request dan $id)
    public function update(Request $request, $id)
    {
        $request->validate([
            'license_plate'  => 'required|string|max:20',
            'owner_name'    => 'required|string|max:255',
            'vehicle_type' => 'required|string|max:100',
            'vehicle_name'  => 'required|string|max:100',
            'status'          => 'required|string',
        ]);

        return redirect()->back()->with('success', 'Data kendaraan berhasil diperbarui!');
    }
}