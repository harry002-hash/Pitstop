<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Chat - {{ $workshop->name ?? 'Bengkel Aju' }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        #messages::-webkit-scrollbar { width: 6px; }
        #messages::-webkit-scrollbar-track { background: transparent; }
        #messages::-webkit-scrollbar-thumb { background-color: #dc2626; border-radius: 9999px; }
    </style>
</head>
<body class="bg-white font-sans text-gray-900 antialiased" data-auth-id="{{ auth()->id() }}" data-auth-name="{{ auth()->user()->username ?? 'You' }}">

    <div class="mx-auto max-w-5xl px-4 py-10 sm:px-6">

        {{-- Vehicle / customer header --}}
        <h1 class="text-4xl font-extrabold tracking-tight text-gray-900 sm:text-5xl">
            {{ $vehicle->plate_number ?? 'KB 8123 XG' }}
        </h1>
        <p class="mt-2 text-lg font-bold text-gray-900 sm:text-2xl">
            {{ $vehicle->owner_name ?? 'Budi Hermanto' }}, {{ $vehicle->model ?? 'Vario 125 Gen 1' }}, {{ $vehicle->type ?? 'Motor' }}
        </p>

        <div class="mt-8 flex gap-3">

            {{-- decorative accent bar --}}
            <div class="hidden w-8 shrink-0 overflow-hidden rounded-2xl sm:flex">
                <div class="flex-1 bg-red-950"></div>
                <div class="flex-1 bg-red-600"></div>
                <div class="flex-1 bg-red-950"></div>
            </div>

            <div class="min-w-0 flex-1">

                {{-- Chat card --}}
                <div class="overflow-hidden rounded-2xl border border-gray-100 shadow-xl">

                    <div class="bg-red-600 px-6 py-4">
                        <h2 class="font-bold text-white">{{ $workshop->name ?? 'Bengkel Aju' }}</h2>
                    </div>

                    <div id="messages" class="h-[420px] scroll-smooth space-y-5 overflow-y-auto bg-gray-50 px-4 py-6 sm:px-6">
                        @forelse ($messages as $message)
                            @php $isMine = $message->sender_id === auth()->id(); @endphp
                            <div class="flex items-end gap-3 {{ $isMine ? 'flex-row-reverse' : '' }}" data-message-id="{{ $message->id }}">
                                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg font-bold text-white {{ $isMine ? 'bg-indigo-700' : 'bg-red-600' }}">
                                    {{ strtoupper(substr($message->sender->username, 0, 1)) }}
                                </div>

                                <div class="max-w-[75%] rounded-2xl bg-white shadow-sm sm:max-w-sm {{ $message->image ? 'p-2' : 'px-4 py-3' }}">
                                    @if ($message->image)
                                        <img src="{{ Storage::url($message->image) }}" alt="Lampiran" class="max-h-64 w-full rounded-xl object-cover">
                                        @if ($message->message)
                                            <p class="px-2 pt-2 text-sm text-gray-900">{{ $message->message }}</p>
                                        @endif
                                    @else
                                        <p class="text-sm text-gray-900">{{ $message->message }}</p>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p id="empty-message" class="text-center text-sm text-gray-400">Belum ada pesan.</p>
                        @endforelse
                    </div>

                    <div class="bg-gray-50 px-4 pb-5 pt-2 sm:px-6">

                        <div id="image-preview-wrap" class="mb-3 hidden">
                            <div class="relative inline-block">
                                <img id="image-preview" src="" alt="Preview" class="h-20 w-20 rounded-lg border border-gray-200 object-cover">
                                <button type="button" id="remove-image" class="absolute -right-2 -top-2 flex h-6 w-6 items-center justify-center rounded-full bg-gray-900 text-xs text-white shadow" aria-label="Hapus gambar">
                                    &times;
                                </button>
                            </div>
                        </div>

                        {{-- TODO: point action at your message-store route --}}
                        <form id="chat-form" method="POST" action="/chat/send" class="flex items-center gap-2 rounded-full border border-gray-200 bg-white px-2 py-2 shadow-sm transition focus-within:ring-2 focus-within:ring-red-400">

                            {{-- TODO: set $receiverId in your controller (the other participant in this conversation) --}}
                            <input type="hidden" id="receiver_id" value="{{ $receiverId ?? '' }}">

                            <input type="file" id="image-input" accept="image/*" class="hidden">

                            <button type="button" id="attach-btn" class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full text-xl font-semibold leading-none text-gray-400 hover:text-gray-600" aria-label="Lampirkan gambar">
                                +
                            </button>

                            <span class="h-6 w-px bg-gray-200"></span>

                            <input type="text" id="message" placeholder="Ketik Pesan..." autocomplete="off" aria-label="Ketik pesan" class="min-w-0 flex-1 bg-transparent px-2 py-1.5 text-sm text-gray-700 placeholder-gray-400 focus:outline-none">
                        </form>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="mt-6 flex justify-end gap-4">
                    <button type="button" id="cancel-btn" class="rounded-lg bg-red-500 px-10 py-3 font-bold text-white shadow transition hover:bg-red-600">
                        Batal
                    </button>
                    <button type="submit" form="chat-form" id="send-btn" class="rounded-lg bg-green-500 px-10 py-3 font-bold text-white shadow transition hover:bg-green-600 disabled:opacity-50">
                        Kirim
                    </button>
                </div>
            </div>
        </div>
    </div>

    <footer class="mt-16 bg-red-950 py-6 text-red-100">
        <div class="mx-auto flex max-w-5xl flex-col items-center justify-between gap-2 px-6 text-sm sm:flex-row">
            <p>&copy; PitStop. All Rights Reserved.</p>
            <p class="text-red-300">V1.0 | Bantuan | Kebijakan Privasi</p>
        </div>
    </footer>

    <script>
        (() => {
            const authId = Number(document.body.dataset.authId);
            const authName = document.body.dataset.authName;
            const list = document.getElementById('messages');
            const form = document.getElementById('chat-form');
            const messageInput = document.getElementById('message');
            const receiverInput = document.getElementById('receiver_id');
            const imageInput = document.getElementById('image-input');
            const attachBtn = document.getElementById('attach-btn');
            const previewWrap = document.getElementById('image-preview-wrap');
            const previewImg = document.getElementById('image-preview');
            const removeImageBtn = document.getElementById('remove-image');
            const cancelBtn = document.getElementById('cancel-btn');
            const sendBtn = document.getElementById('send-btn');
            const csrf = document.querySelector('meta[name="csrf-token"]').content;

            let selectedImage = null;

            list.scrollTop = list.scrollHeight;

            function resetDraft() {
                messageInput.value = '';
                selectedImage = null;
                imageInput.value = '';
                previewImg.src = '';
                previewWrap.classList.add('hidden');
            }

            attachBtn.addEventListener('click', () => imageInput.click());

            imageInput.addEventListener('change', () => {
                const file = imageInput.files[0];
                if (!file) return;

                if (file.size > 5 * 1024 * 1024) {
                    alert('Ukuran gambar maksimal 5MB.');
                    imageInput.value = '';
                    return;
                }

                selectedImage = file;
                previewImg.src = URL.createObjectURL(file);
                previewWrap.classList.remove('hidden');
            });

            removeImageBtn.addEventListener('click', () => {
                selectedImage = null;
                imageInput.value = '';
                previewImg.src = '';
                previewWrap.classList.add('hidden');
            });

            cancelBtn.addEventListener('click', resetDraft);

            function bubble({ sender, senderId, message, imageUrl }) {
                const mine = Number(senderId) === authId;
                const initial = (sender || 'U').charAt(0).toUpperCase();

                const row = document.createElement('div');
                row.className = `flex items-end gap-3 ${mine ? 'flex-row-reverse' : ''}`;

                const avatar = document.createElement('div');
                avatar.className = `flex h-11 w-11 shrink-0 items-center justify-center rounded-lg font-bold text-white ${mine ? 'bg-indigo-700' : 'bg-red-600'}`;
                avatar.textContent = initial;

                const card = document.createElement('div');
                card.className = `max-w-[75%] sm:max-w-sm rounded-2xl bg-white shadow-sm ${imageUrl ? 'p-2' : 'px-4 py-3'}`;

                if (imageUrl) {
                    const img = document.createElement('img');
                    img.src = imageUrl;
                    img.alt = 'Lampiran';
                    img.className = 'max-h-64 w-full rounded-xl object-cover';
                    card.appendChild(img);
                }

                if (message) {
                    const p = document.createElement('p');
                    p.className = `text-sm text-gray-900 ${imageUrl ? 'px-2 pt-2' : ''}`;
                    p.textContent = message;
                    card.appendChild(p);
                }

                row.appendChild(avatar);
                row.appendChild(card);
                return row;
            }

            function appendMessage(data) {
                document.getElementById('empty-message')?.remove();
                list.appendChild(bubble({
                    sender: data.sender,
                    senderId: data.sender_id,
                    message: data.message,
                    imageUrl: data.image_url,
                }));
                list.scrollTop = list.scrollHeight;
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

                const text = messageInput.value.trim();
                if (!text && !selectedImage) return;

                const body = new FormData();
                body.append('receiver_id', receiverInput.value);
                body.append('message', text);
                if (selectedImage) body.append('image', selectedImage);

                sendBtn.disabled = true;

                try {
                    const res = await fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrf,
                            'Accept': 'application/json',
                            // No Content-Type here — the browser sets the correct
                            // multipart/form-data boundary automatically for FormData.
                            ...(window.Echo?.socketId()
                                ? { 'X-Socket-ID': window.Echo.socketId() }
                                : {}),
                        },
                        body,
                    });

                    const data = await res.json().catch(() => ({}));

                    if (!res.ok) {
                        console.error(data);
                        alert(data.message ?? 'Gagal mengirim pesan.');
                        return;
                    }

                    appendMessage({
                        sender_id: authId,
                        sender: authName,
                        message: text,
                        image_url: data.image_url ?? (selectedImage ? previewImg.src : null),
                    });

                    resetDraft();
                } catch (err) {
                    console.error(err);
                    alert('Gagal mengirim pesan. Periksa koneksi Anda.');
                } finally {
                    sendBtn.disabled = false;
                }
            });
        })();
    </script>

</body>
</html>
