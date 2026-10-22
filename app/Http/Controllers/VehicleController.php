<?php

namespace App\Http\Controllers;

use App\Events\VehicleStatusUpdated;
use App\Http\Requests\StoreVehicleRequest;
use App\Http\Requests\UpdateVehicleRequest;
use App\Models\Message;
use App\Models\Vehicle;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Gate;
use Throwable;

class VehicleController extends Controller
{
    public function index(): View
    {
        /** @var Collection<int, Vehicle> $vehicles */
        $vehicles = Vehicle::with('owner:id,username')->latest('id')->get();
        $counts = $vehicles->countBy('status');

        return view('owner.vehicles.index', [
            'vehicles' => $vehicles,
            'antrianTungguCount' => $counts->get(Vehicle::STATUS_BELUM_SERVIS, 0),
            'prosesPengerjaanCount' => $counts->get(Vehicle::STATUS_SEDANG_SERVIS, 0),
            'thirdCardCount' => $counts->get(Vehicle::STATUS_SELESAI, 0),
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
        Gate::authorize('update', $vehicle);

        return view('edit-kendaraan', [
            'vehicle' => $vehicle->load('owner:id,username'),
            'statuses' => Vehicle::statuses(),
        ]);
    }

    public function update(UpdateVehicleRequest $request, Vehicle $vehicle): RedirectResponse
    {
        $data = $request->safe()->except('plate_password');

        if ($request->filled('plate_password')) {
            $data['plate_password'] = $request->validated('plate_password');
        }

        $statusChanged = isset($data['status']) && $data['status'] !== $vehicle->status;

        $vehicle->update($data);

        if ($statusChanged) {
            try {
                broadcast(new VehicleStatusUpdated($vehicle->refresh()));
            } catch (Throwable $exception) {
                report($exception);
            }

            // Motor selesai -> kabari pemiliknya lewat chat (sekaligus jadi popup).
            if ($data['status'] === Vehicle::STATUS_SELESAI && $vehicle->owner) {
                Message::deliver(
                    $request->user(),
                    $vehicle->owner,
                    'Kabar gembira! Motor '.$vehicle->plate_number.' kamu sudah SELESAI diservis. Silakan ambil di bengkel ya!',
                );
            }
        }

        return $request->user()->isStaff()
            ? redirect()->route('owner.vehicles.index')->with('status', 'Data motor diperbarui.')
            : redirect()->route('customer.dashboard')->with('status', 'Data kendaraan diperbarui.');
    }

    /**
     * Kirim pengingat ke customer pemilik motor (masuk sebagai pesan chat).
     */
    public function remind(Request $request, Vehicle $vehicle): JsonResponse
    {
        $vehicle->loadMissing('owner:id,username');

        if (! $vehicle->owner) {
            return response()->json(['message' => 'Motor belum diklaim customer.'], 422);
        }

        Message::deliver(
            $request->user(),
            $vehicle->owner,
            'Pengingat dari bengkel: status motor '.$vehicle->plate_number.' kamu saat ini "'.$vehicle->statusLabel().'". Pantau terus ya!',
        );

        return response()->json([
            'success' => true,
            'to' => $vehicle->owner->username,
        ]);
    }
}
