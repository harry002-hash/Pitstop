{{-- <!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Chat Bengkel</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f3f4f6;
        }

        .chat-container {
            width: 100%;
            max-width: 800px;
            height: 100vh;
            margin: auto;
            background: white;
            display: flex;
            flex-direction: column;
        }

        .chat-header {
            padding: 18px 20px;
            border-bottom: 1px solid #ddd;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .chat-header h2 {
            margin: 0 0 5px;
            font-size: 20px;
        }

        .chat-header small {
            color: #777;
        }

        .logout-button {
            border: none;
            background: #dc2626;
            color: white;
            padding: 9px 15px;
            border-radius: 6px;
            cursor: pointer;
        }

        .logout-button:hover {
            background: #b91c1c;
        }

        .messages {
            flex: 1;
            overflow-y: auto;
            padding: 20px;
        }

        .message {
            display: flex;
            margin-bottom: 15px;
        }

        .message.mine {
            justify-content: flex-end;
        }

        .message-content {
            max-width: 70%;
        }

        .sender {
            font-size: 12px;
            color: #666;
            margin-bottom: 5px;
        }

        .mine .sender {
            text-align: right;
        }

        .bubble {
            background: #e5e7eb;
            padding: 10px 14px;
            border-radius: 12px;
            word-wrap: break-word;
        }

        .mine .bubble {
            background: #dc2626;
            color: white;
        }

        .time {
            font-size: 10px;
            color: #999;
            margin-top: 5px;
        }

        .mine .time {
            text-align: right;
        }

        .message-image {
            display: block;
            max-width: 250px;
            max-height: 250px;
            margin-top: 8px;
            border-radius: 8px;
        }

        .input-container {
            border-top: 1px solid #ddd;
            padding: 15px;
        }

        .image-preview {
            margin-bottom: 10px;
        }

        .image-preview img {
            max-width: 150px;
            max-height: 150px;
            border-radius: 8px;
        }

        .chat-form {
            display: flex;
            gap: 8px;
        }

        .chat-form input[type="text"] {
            flex: 1;
            padding: 11px;
            border: 1px solid #ccc;
            border-radius: 7px;
            outline: none;
        }

        .chat-form input[type="text"]:focus {
            border-color: #dc2626;
        }

        .file-button {
            display: flex;
            align-items: center;
            padding: 0 12px;
            border: 1px solid #ccc;
            border-radius: 7px;
            cursor: pointer;
            background: white;
        }

        .file-button:hover {
            background: #f3f4f6;
        }

        .send-button {
            border: none;
            background: #dc2626;
            color: white;
            padding: 0 18px;
            border-radius: 7px;
            cursor: pointer;
        }

        .send-button:hover {
            background: #b91c1c;
        }

        .empty-message {
            text-align: center;
            color: #999;
            margin-top: 50px;
        }

        @media (max-width: 600px) {
            .message-content {
                max-width: 85%;
            }

            .chat-form {
                gap: 5px;
            }

            .file-button {
                padding: 0 10px;
            }

            .send-button {
                padding: 0 12px;
            }
        }
    </style>
</head>

<body>

<div class="chat-container">

    <div class="chat-header">

        <div>
            <h2>Chat Bengkel</h2>

            @auth
                <small>
                    Login sebagai: {{ auth()->user()->username }}
                </small>
            @endauth
        </div>

        @auth
            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button type="submit" class="logout-button">
                    Logout
                </button>
            </form>
        @endauth

    </div>

    <div class="messages" id="messages">

        @forelse ($messages as $message)

            <div class="message {{ $message->sender_id === auth()->id() ? 'mine' : '' }}">

                <div class="message-content">

                    <div class="sender">
                        {{ $message->sender->username }}
                    </div>

                    <div class="bubble">

                        @if ($message->message)
                            <div>
                                {{ $message->message }}
                            </div>
                        @endif

                        @if ($message->image)
                            <img
                                src="{{ asset('storage/' . $message->image) }}"
                                alt="Gambar"
                                class="message-image"
                            >
                        @endif

                    </div>

                    <div class="time">
                        {{ $message->created_at->format('H:i') }}
                    </div>

                </div>

            </div>

        @empty

            <div class="empty-message">
                Belum ada pesan.
            </div>

        @endforelse

    </div>

    <div class="input-container">

        <div
            class="image-preview"
            id="image-preview"
            style="display: none;"
        >
            <img
                id="preview-image"
                src=""
                alt="Preview"
            >
        </div>

        <form
            id="chat-form"
            class="chat-form"
            enctype="multipart/form-data"
        >

            @csrf

            <label class="file-button">
                📷

                <input
                    type="file"
                    id="image"
                    name="image"
                    accept="image/*"
                    hidden
                >
            </label>

            <input
                type="text"
                id="message"
                name="message"
                placeholder="Tulis pesan..."
                autocomplete="off"
            >

            <button
                type="submit"
                class="send-button"
            >
                Kirim
            </button>

        </form>

    </div>

</div>

<script>

const form = document.getElementById('chat-form');
const messageInput = document.getElementById('message');
const imageInput = document.getElementById('image');

const preview = document.getElementById('image-preview');
const previewImage = document.getElementById('preview-image');

const messages = document.getElementById('messages');

imageInput.addEventListener('change', function () {

    const file = this.files[0];

    if (!file) {
        preview.style.display = 'none';
        previewImage.src = '';

        return;
    }

    const reader = new FileReader();

    reader.onload = function (event) {
        previewImage.src = event.target.result;
        preview.style.display = 'block';
    };

    reader.readAsDataURL(file);
});

form.addEventListener('submit', async function (event) {

    event.preventDefault();

    const message = messageInput.value.trim();
    const image = imageInput.files[0];

    if (!message && !image) {
        return;
    }

    const formData = new FormData();

    formData.append('message', message);

    if (image) {
        formData.append('image', image);
    }

    try {

        const response = await fetch(
            "{{ route('chat.send') }}",
            {
                method: 'POST',

                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },

                body: formData
            }
        );

        const data = await response.json();

        if (!response.ok) {
            console.error(data);
            alert('Pesan gagal dikirim.');

            return;
        }

        messageInput.value = '';
        imageInput.value = '';

        preview.style.display = 'none';
        previewImage.src = '';

        location.reload();

    } catch (error) {

        console.error(error);

        alert('Terjadi kesalahan.');

    }

});

messages.scrollTop = messages.scrollHeight;

</script>

</body>
</html> --}}