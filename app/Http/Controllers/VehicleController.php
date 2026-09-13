<?php

namespace App\Http\Controllers;

use App\Events\VehicleStatusUpdated;
use App\Http\Requests\StoreVehicleRequest;
use App\Http\Requests\UpdateVehicleRequest;
use App\Models\Vehicle;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class VehicleController extends Controller
{
    public function index(): View
    {
        $vehicles = Vehicle::with('owner:id,username')->latest()->get();

        return view('owner.vehicles.index', [
            'vehicles' => $vehicles,
            'antrianTungguCount' => $vehicles->where('status', Vehicle::STATUS_BELUM_SERVIS)->count(),
            'prosesPengerjaanCount' => $vehicles->where('status', Vehicle::STATUS_SEDANG_SERVIS)->count(),
            'thirdCardCount' => $vehicles->where('status', Vehicle::STATUS_SELESAI)->count(),
        ]);
    }

    public function create(): View
    {
        return view('owner.vehicles.create', ['statuses' => Vehicle::statuses()]);
    }

    public function store(StoreVehicleRequest $request): RedirectResponse
    {
        Vehicle::create($request->validated());

        return redirect()->route('owner.vehicles.index')->with('status', 'Data motor tersimpan.');
    }

    public function edit(Vehicle $vehicle): View
    {
        return view('owner.vehicles.edit', ['vehicle' => $vehicle, 'statuses' => Vehicle::statuses()]);
    }

    public function update(UpdateVehicleRequest $request, Vehicle $vehicle): RedirectResponse
    {
        $data = $request->validated();

        if (empty($data['plate_password'])) {
            unset($data['plate_password']);
        }

        $statusChanged = $data['status'] !== $vehicle->status;

        $vehicle->update($data);

        if ($statusChanged) {
            broadcast(new VehicleStatusUpdated($vehicle->refresh()));
        }

        return redirect()->route('owner.vehicles.index')->with('status', 'Data motor diperbarui.');
    }
}
