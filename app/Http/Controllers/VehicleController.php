<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Events\VehicleStatusUpdated;
use App\Http\Requests\StoreVehicleRequest;
use App\Http\Requests\UpdateVehicleRequest;
use App\Models\Message;
use App\Models\Vehicle;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;

class VehicleController extends Controller
{
    public function index(): View
    {
        $vehicles = Vehicle::with('owner:id,username')->latest()->get();

        return view('owner.vehicles.index', [
            'vehicles' => $vehicles,
            'statuses' => Vehicle::statuses(),
        ]);
    }

    public function store(StoreVehicleRequest $request): RedirectResponse
    {
        Vehicle::create($request->validated());

        return redirect()->route('owner.vehicles.index')->with('status', 'Data motor tersimpan.');
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

            // Motor baru aja selesai -> kabarin pemiliknya gede-gedean via chat.
            if ($data['status'] === Vehicle::STATUS_SELESAI && $vehicle->user_id) {
                $done = Message::create([
                    'sender_id' => auth()->id(),
                    'receiver_id' => $vehicle->user_id,
                    'message' => 'Kabar gembira! Motor '.$vehicle->plate_number.' kamu sudah SELESAI diservis. Silakan ambil di bengkel ya!',
                ]);

                $done->load('sender:id,username');
                broadcast(new MessageSent($done, (int) $vehicle->user_id))->toOthers();
            }
        }

        return redirect()->route('owner.vehicles.index')->with('status', 'Data motor diperbarui.');
    }

    // Reminder ke customer pemilik motor: masuk sebagai pesan chat dari staff,
    // jadi muncul sebagai popup + bunyi di dashboard customer.
    public function remind(Vehicle $vehicle): JsonResponse
    {
        $vehicle->load('owner:id,username');

        if (! $vehicle->owner) {
            return response()->json(['message' => 'Motor belum diklaim customer.'], 422);
        }

        $message = Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $vehicle->owner->id,
            'message' => 'Pengingat dari bengkel: status motor '.$vehicle->plate_number.' kamu saat ini "'.$vehicle->statusLabel().'". Pantau terus ya!',
        ]);

        $message->load('sender:id,username');
        broadcast(new MessageSent($message, (int) $vehicle->owner->id))->toOthers();

        return response()->json([
            'success' => true,
            'to' => $vehicle->owner->username,
        ]);
    }

    // Riwayat notif buat dropdown lonceng: chat masuk + klaim terbaru.
    public function notifications(): JsonResponse
    {
        $chats = Message::with('sender:id,username,role')
            ->latest()
            ->limit(20)
            ->get()
            ->filter(fn (Message $m) => $m->sender && ! $m->sender->isAdmin() && ! $m->sender->isOwner())
            ->take(7)
            ->map(fn (Message $m) => [
                'key' => 'chat:'.$m->id,
                'type' => 'chat',
                'title' => 'Chat baru dari '.$m->sender->username,
                'body' => $m->message !== '' ? Str::limit($m->message, 80) : 'Mengirim gambar',
                'url' => route('chat', ['u' => $m->sender_id]),
                'time' => $m->created_at->format('H:i, d M'),
                'at' => $m->created_at->toIso8601String(),
            ]);

        $claims = Vehicle::with('owner:id,username')
            ->whereNotNull('user_id')
            ->latest('updated_at')
            ->limit(5)
            ->get()
            ->map(fn (Vehicle $v) => [
                'key' => 'claim:'.$v->id.':'.$v->updated_at->timestamp,
                'type' => 'claim',
                'title' => 'Klaim: '.$v->plate_number,
                'body' => 'Diklaim oleh '.($v->owner?->username ?? 'customer'),
                'url' => route('owner.vehicles.index'),
                'time' => $v->updated_at->format('H:i, d M'),
                'at' => $v->updated_at->toIso8601String(),
            ]);

        $items = $chats->concat($claims)->sortByDesc('at')->values()->take(10);

        return response()->json(['notifications' => $items]);
    }
}
