<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Chat</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body data-auth-id="{{ auth()->id() }}">

    <h1>Chat</h1>

    <div id="messages">
        @foreach ($messages as $message)
            <div>
                <b>{{ $message->sender->username }}</b>:
                {{ $message->message }}
            </div>
        @endforeach
    </div>

    <form id="chat-form" action="{{ route('chat.send') }}" method="POST">

        <input
            type="number"
            id="receiver_id"
            placeholder="Receiver ID"
            required
        >

        <input
            type="text"
            id="message"
            placeholder="Message"
            required
        >

        <button type="submit">
            Send
        </button>

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

            const item = document.createElement('div');
            const mine = Number(data.sender_id) === authId ? ' (you)' : '';
            const time = data.created_at ? new Date(data.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) : '';

            item.textContent = `${data.sender ?? 'User'}${mine}: ${data.message} ${time}`;
            list.appendChild(item);
        }

        // Realtime: channel privat milik user yang login, event `.message.sent`.
        // app.js (Echo) dimuat lewat vite di atas, dan `php artisan reverb:start` harus jalan.
        window.addEventListener('load', () => {
            if (!window.Echo) {
                console.warn('Echo not loaded. Run: npm run dev (or build) + php artisan reverb:start');
                return;
            }
            window.Echo.private('App.Models.User.' + authId).listen('.message.sent', appendMessage);
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