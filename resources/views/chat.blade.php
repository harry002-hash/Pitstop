<!DOCTYPE html>
<html>
<head>
    <title>Chat</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>

    <h1>Chat</h1>

    <div id="messages">
        @foreach ($messages as $message)
            <div>
                <b>{{ $message->sender->username }}</b>:
                {{ $message->message }}
            </div>
        @endforeach
    </div>

    <form id="chat-form">

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

</body>
</html>