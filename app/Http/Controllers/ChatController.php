<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Message;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index()
    {
        $messages = Message::with('sender')->latest()->get()->reverse();

        return view('chat', compact('messages'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'receiver_id' => ['required', 'exists:users,id'],
            'message' => ['required', 'string','max:1000'],
        ]);

        $message = Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
        ]);

        $message->load('sender');
        broadcast(new MessageSent($message))->toOthers();

        return response()->json([
            'success' => true,
            'message' => $message,
        ]);
    }
}
