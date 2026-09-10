<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Chat</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body data-auth-id="{{ auth()->id() }}">

    <h1>Chat</h1>
    <p>Logged in as: {{ auth()->user()->username }}</p>

    <nav>
        <a href="{{ route('dashboard') }}">Dashboard</a>
        <form method="POST" action="{{ route('logout') }}" style="display:inline">
            @csrf
            <button type="submit">Logout</button>
        </form>
    </nav>

    <hr>

    {{-- Messages (server-rendered, oldest first) --}}
    <ul id="messages">
        @forelse ($messages as $message)
            <li data-id="{{ $message->id }}">
                <strong>{{ $message->sender->username }}</strong>:
                {{ $message->message }}
                <small>{{ $message->created_at->format('H:i') }}</small>
            </li>
        @empty
            <li id="empty-message">No messages yet.</li>
        @endforelse
    </ul>

    <hr>

    {{-- Send form: POST route('chat.send') via fetch --}}
    <form id="chat-form" method="POST" action="{{ route('chat.send') }}">
        @csrf

        <label for="receiver_id">To:</label>
        <select id="receiver_id" name="receiver_id" required>
            <option value="">-- select user --</option>
            @foreach ($users as $user)
                <option value="{{ $user->id }}">{{ $user->username }}</option>
            @endforeach
        </select>

        <input
            type="text"
            id="message"
            name="message"
            placeholder="Type a message..."
            autocomplete="off"
            required
            maxlength="1000"
        >

        <button type="submit">Send</button>
    </form>

    <script>
        const authId = Number(document.body.dataset.authId);
        const list = document.getElementById('messages');
        const form = document.getElementById('chat-form');
        const messageInput = document.getElementById('message');
        const receiverSelect = document.getElementById('receiver_id');
        const csrf = document.querySelector('meta[name="csrf-token"]').content;

        function appendMessage(data) {
            document.getElementById('empty-message')?.remove();

            const li = document.createElement('li');
            const mine = Number(data.sender_id) === authId ? ' (you)' : '';
            const time = data.created_at ? new Date(data.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) : '';

            li.textContent = `${data.sender ?? 'User'}${mine}: ${data.message} ${time}`;
            list.appendChild(li);
        }

        // Realtime: listen on public `chat` channel, event `.message.sent`.
        // app.js (Echo) must be loaded via vite above, and `php artisan reverb:start` running.
        window.addEventListener('load', () => {
            if (!window.Echo) {
                console.warn('Echo not loaded. Run: npm run dev (or build) + php artisan reverb:start');
                return;
            }
            window.Echo.channel('chat').listen('.message.sent', appendMessage);
        });

        form.addEventListener('submit', async (e) => {
            e.preventDefault();

            const res = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrf,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    // Required for broadcast(...)->toOthers() so the sender
                    // doesn't receive its own event back.
                    ...(window.Echo?.socketId()
                        ? { 'X-Socket-ID': window.Echo.socketId() }
                        : {}),
                },
                body: JSON.stringify({
                    receiver_id: Number(receiverSelect.value),
                    message: messageInput.value.trim(),
                }),
            });

            const data = await res.json().catch(() => ({}));

            if (!res.ok) {
                console.error(data);
                alert(data.message ?? 'Failed to send message.');
                return;
            }

            // Sender renders its own message instantly (toOthers excludes it from broadcast).
            appendMessage({
                sender_id: authId,
                sender: 'You',
                message: messageInput.value.trim(),
                created_at: new Date().toISOString(),
            });

            messageInput.value = '';
        });
    </script>

</body>
</html>
