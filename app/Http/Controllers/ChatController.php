<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Events\StaffAlert;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ChatController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $isStaff = $user->isAdmin() || $user->isOwner();
        $staffIds = User::whereIn('role', ['admin', 'owner'])->pluck('id');

        if ($isStaff) {
            $activeCustomerId = $request->query('u') ? (int) $request->query('u') : null;
            $peer = $activeCustomerId
                ? User::where('id', $activeCustomerId)->where('role', 'customer')->first()
                : null;

            // Inbox: customer yang pernah chat (ambil dari 200 pesan terakhir) + peer aktif.
            $recentIds = Message::latest()->limit(200)->pluck('sender_id')
                ->merge(Message::latest()->limit(200)->pluck('receiver_id'))
                ->unique()->values();
            $customers = User::where('role', 'customer')
                ->whereIn('id', $recentIds)
                ->orderBy('username')
                ->limit(50)
                ->get();
            if ($peer && ! $customers->contains('id', $peer->id)) {
                $customers->push($peer);
            }

            $messages = $peer
                ? Message::with('sender:id,username')
                    ->where(function ($q) use ($peer, $staffIds): void {
                        $q->where(function ($q) use ($peer, $staffIds): void {
                            $q->where('sender_id', $peer->id)->whereIn('receiver_id', $staffIds);
                        })->orWhere(function ($q) use ($peer, $staffIds): void {
                            $q->whereIn('sender_id', $staffIds)->where('receiver_id', $peer->id);
                        });
                    })
                    ->orderBy('id')
                    ->limit(200)
                    ->get()
                : collect();

            $threadCustomerId = $peer?->id;
            $vehicle = $peer?->vehicles()->latest()->first();
            $headerOwner = $peer?->username;

            return view('chat', compact('messages', 'customers', 'peer', 'isStaff', 'threadCustomerId', 'vehicle', 'headerOwner'));
        }

        // Customer: hanya thread miliknya sendiri. Tidak bisa intip customer lain.
        $support = Message::where('sender_id', $user->id)
            ->orWhere('receiver_id', $user->id)
            ->latest()
            ->first();
        $supportId = null;
        if ($support) {
            $candidate = $support->sender_id === $user->id ? $support->receiver_id : $support->sender_id;
            if ($staffIds->contains($candidate)) {
                $supportId = $candidate;
            }
        }
        $supportId ??= $staffIds->first();
        $peer = $supportId ? User::find($supportId) : null;

        $messages = Message::with('sender:id,username')
            ->where('sender_id', $user->id)
            ->orWhere('receiver_id', $user->id)
            ->orderBy('id')
            ->limit(200)
            ->get();

        $customers = collect();
        $threadCustomerId = $user->id;
        $vehicle = $user->vehicles()->latest()->first();
        $headerOwner = $user->username;

        return view('chat', compact('messages', 'customers', 'peer', 'isStaff', 'threadCustomerId', 'vehicle', 'headerOwner'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'message' => ['nullable', 'string', 'max:1000'],
            'image' => ['nullable', 'image', 'max:5120'],
            // Hanya staff yang wajib isi tujuan. Customer diabaikan (auto ke admin).
            'to_user_id' => ['nullable', 'exists:users,id'],
            'receiver_id' => ['nullable', 'exists:users,id'],
        ]);

        if (! $request->filled('message') && ! $request->hasFile('image')) {
            return response()->json(['message' => 'Pesan atau gambar wajib diisi.'], 422);
        }

        $user = $request->user();
        $isStaff = $user->isAdmin() || $user->isOwner();

        if ($isStaff) {
            $targetId = (int) ($request->input('to_user_id') ?? $request->input('receiver_id') ?? 0);
            if (! $targetId) {
                return response()->json(['message' => 'Pilih customer tujuan dulu (parameter u).'], 422);
            }
            $peer = User::findOrFail($targetId);
            if ($peer->role !== 'customer') {
                return response()->json(['message' => 'Staff hanya bisa chat ke customer.'], 422);
            }
            if ($peer->id === $user->id) {
                return response()->json(['message' => 'Tidak bisa chat ke diri sendiri.'], 422);
            }
            $receiverId = $peer->id;
        } else {
            // Customer -> selalu ke support (staff terakhir yg balas, fallback staff pertama).
            $staffIds = User::whereIn('role', ['admin', 'owner'])->pluck('id');
            if ($staffIds->isEmpty()) {
                return response()->json(['message' => 'Support belum tersedia.'], 422);
            }
            $last = Message::where('sender_id', $user->id)
                ->orWhere('receiver_id', $user->id)
                ->latest()
                ->first();
            $receiverId = $staffIds->first();
            if ($last) {
                $candidate = $last->sender_id === $user->id ? $last->receiver_id : $last->sender_id;
                if ($staffIds->contains($candidate)) {
                    $receiverId = $candidate;
                }
            }
            $peer = User::find($receiverId);
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('chat', 'public');
        }

        $message = Message::create([
            'sender_id' => $user->id,
            'receiver_id' => $receiverId,
            'message' => $request->input('message', ''),
            'image' => $imagePath,
        ]);

        $message->load('sender:id,username');
        $customerId = $isStaff ? (int) $receiverId : (int) $user->id;
        broadcast(new MessageSent($message, $customerId))->toOthers();

        // Kabarin semua staff kalau ada chat masuk dari customer.
        if (! $isStaff) {
            broadcast(new StaffAlert(
                type: 'chat',
                title: 'Chat baru dari '.$user->username,
                body: $message->message !== '' ? Str::limit($message->message, 80) : 'Mengirim gambar',
                url: route('chat', ['u' => $user->id]),
                customerId: (int) $user->id,
            ))->toOthers();
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'image_url' => $imagePath ? asset('storage/'.$imagePath) : null,
        ]);
    }
}
