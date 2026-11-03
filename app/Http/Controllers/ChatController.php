<?php

namespace App\Http\Controllers;

use App\Http\Requests\SendMessageRequest;
use App\Models\Message;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index(Request $request): View
    {
        $messages = Message::with('sender:id,username')
            ->visibleTo($request->user())
            ->latest('id')
            ->limit(200)
            ->get()
            ->reverse()
            ->values();

        return view('chat', compact('messages'));
    }

    public function send(SendMessageRequest $request): JsonResponse
    {
        $message = Message::deliver(
            $request->user(),
            User::findOrFail($request->validated('receiver_id')),
            $request->validated('message'),
        );

        return response()->json([
            'success' => true,
            'message' => $message,
        ]);
    }
}
