<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Http\Requests\SendMessageRequest;
use App\Models\Message;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;

class ChatController extends Controller
{
    public function index(): View
    {
        $messages = Message::with('sender:id,username')
            ->oldest()
            ->take(100)
            ->get();

        $users = User::query()
            ->where('id', '!=', auth()->id())
            ->select(['id', 'username'])
            ->orderBy('username')
            ->get();

        return view('chat', compact('messages', 'users'));
    }

    public function send(SendMessageRequest $request): JsonResponse
    {
        $message = Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $request->validated('receiver_id'),
            'message' => $request->validated('message'),
        ]);

        $message->load('sender:id,username');

        broadcast(new MessageSent($message))->toOthers();

        return response()->json([
            'success' => true,
            'message' => $message,
        ], 201);
    }
}
