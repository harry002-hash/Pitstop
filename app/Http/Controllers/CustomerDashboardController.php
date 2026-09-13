<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

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

    // Notif buat customer: balasan chat staff + status motornya sendiri.
    public function notifications(): JsonResponse
    {
        $user = auth()->user();
        $staffIds = User::whereIn('role', ['admin', 'owner'])->pluck('id');

        $chats = Message::with('sender:id,username')
            ->where('receiver_id', $user->id)
            ->whereIn('sender_id', $staffIds)
            ->latest()
            ->limit(5)
            ->get()
            ->map(fn (Message $m) => [
                'key' => 'chat:'.$m->id,
                'type' => 'chat',
                'title' => 'Balasan dari '.($m->sender?->username ?? 'bengkel'),
                'body' => $m->message !== '' ? Str::limit($m->message, 80) : 'Mengirim gambar',
                'url' => route('chat'),
                'time' => $m->created_at->format('H:i, d M'),
                'at' => $m->created_at->toIso8601String(),
            ]);

        $vehicle = $user->vehicles()->latest()->first();

        return response()->json([
            'notifications' => $chats->values(),
            'vehicle' => $vehicle ? [
                'id' => $vehicle->id,
                'status' => $vehicle->status,
                'status_label' => $vehicle->statusLabel(),
                'updated_at' => $vehicle->updated_at->toIso8601String(),
            ] : null,
        ]);
    }
}
