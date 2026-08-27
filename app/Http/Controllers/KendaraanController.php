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
            'plat_kendaraan'  => 'KB 8123 XG',
            'nama_pemilik'    => 'Budi Heremanto',
            'jenis_kendaraan' => 'Motor',
            'nama_kendaraan'  => 'Vario 125 Gen 1',
            'status'          => 'Dikerjakan',
        ];

        return view('edit-kendaraan', compact('kendaraan'));
    }

    // Method proses simpan update (menerima $request dan $id)
    public function update(Request $request, $id)
    {
        $request->validate([
            'plat_kendaraan'  => 'required|string|max:20',
            'nama_pemilik'    => 'required|string|max:255',
            'jenis_kendaraan' => 'required|string|max:100',
            'nama_kendaraan'  => 'required|string|max:100',
            'status'          => 'required|string',
        ]);

        return redirect()->back()->with('success', 'Data kendaraan berhasil diperbarui!');
    }
}