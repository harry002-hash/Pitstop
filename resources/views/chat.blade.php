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
<body class="bg-white font-sans text-gray-900 antialiased"
    data-auth-id="{{ auth()->id() }}"
    data-auth-name="{{ auth()->user()->username ?? 'You' }}"
    data-thread="{{ $threadCustomerId ?? '' }}"
    data-is-staff="{{ ($isStaff ?? false) ? '1' : '0' }}"
    data-peer-id="{{ $peer?->id ?? '' }}">

    <div class="flex min-h-screen flex-col">
        <div class="flex flex-1 flex-col md:flex-row">

            <x-app-sidebar>
                <x-slot:nav>
                    <x-sidebar-link :href="route('dashboard')" :active="false">Dashboard</x-sidebar-link>
                    <x-sidebar-link :href="route('chat')" :active="true">Chat</x-sidebar-link>
                </x-slot:nav>
            </x-app-sidebar>

            <div class="min-w-0 flex-1 px-4 py-6 sm:px-6">
                <div class="mx-auto max-w-3xl">

        {{-- Vehicle / customer header --}}
        <h1 class="text-2xl font-extrabold tracking-tight text-gray-900 sm:text-3xl">
            {{ $vehicle->plate_number ?? 'Belum ada kendaraan' }}
        </h1>
        <p class="mt-1 text-sm font-bold text-gray-900 sm:text-base">
            {{ $headerOwner ?? auth()->user()->username }}, {{ $vehicle->vehicle_name ?? '—' }}, Motor
        </p>

        <div class="mt-5 flex gap-3">

            {{-- decorative accent bar --}}
            <div class="hidden w-8 shrink-0 overflow-hidden rounded-2xl sm:flex">
                <div class="flex-1 bg-red-950"></div>
                <div class="flex-1 bg-red-600"></div>
                <div class="flex-1 bg-red-950"></div>
            </div>

            <div class="min-w-0 flex-1">

                @if ($isStaff ?? false)
                    {{-- Staff: inbox di kiri, thread aktif di kanan --}}
                    <div class="grid gap-4 md:grid-cols-[240px_1fr]">
                        <aside class="overflow-hidden rounded-2xl border border-gray-100 shadow-xl">
                            <div class="bg-gray-900 px-4 py-3">
                                <h2 class="font-bold text-white">Inbox Customer</h2>
                            </div>
                            <div class="max-h-[480px] overflow-y-auto bg-white">
                                @forelse ($customers as $customer)
                                    <a href="{{ route('chat', ['u' => $customer->id]) }}"
                                        class="block border-b border-gray-100 px-4 py-3 hover:bg-gray-50 {{ ($peer?->id === $customer->id) ? 'bg-red-50' : '' }}">
                                        <p class="font-bold">{{ $customer->username }}</p>
                                        <p class="text-xs text-gray-500">ID: {{ $customer->id }}</p>
                                    </a>
                                @empty
                                    <p class="px-4 py-6 text-sm text-gray-400">Belum ada chat masuk.</p>
                                @endforelse
                            </div>
                        </aside>
                @endif

                {{-- Chat card --}}
                <div class="overflow-hidden rounded-2xl border border-gray-100 shadow-xl">

                    <div class="bg-red-600 px-6 py-4">
                        <h2 class="font-bold text-white">
                            @if ($isStaff ?? false)
                                {{ $peer ? 'Chat dengan '.$peer->username : 'Pilih customer dulu' }}
                            @else
                                {{ $peer?->username ?? ($workshop->name ?? 'Bengkel Aju') }} (Admin)
                            @endif
                        </h2>
                    </div>

                    <div id="messages" class="h-[360px] scroll-smooth space-y-4 overflow-y-auto bg-gray-50 px-3 py-4 sm:px-5">
                        @forelse ($messages as $message)
                            @php $isMine = $message->sender_id === auth()->id(); @endphp
                            <div class="flex items-end gap-3 {{ $isMine ? 'flex-row-reverse' : '' }}" data-message-id="{{ $message->id }}">
                                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg font-bold text-white {{ $isMine ? 'bg-indigo-700' : 'bg-red-600' }}">
                                    {{ strtoupper(substr($message->sender?->username ?? '?', 0, 1)) }}
                                </div>

                                <div class="max-w-[75%] rounded-2xl bg-white shadow-sm sm:max-w-sm {{ $message->image ? 'p-2' : 'px-4 py-3' }}">
                                    @if ($message->image)
                                        <img src="{{ \Illuminate\Support\Facades\Storage::url($message->image) }}" alt="Lampiran" class="max-h-64 w-full rounded-xl object-cover">
                                        @if ($message->message)
                                            <p class="px-2 pt-2 text-sm text-gray-900">{{ $message->message }}</p>
                                        @endif
                                    @else
                                        <p class="text-sm text-gray-900">{{ $message->message }}</p>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p id="empty-message" class="text-center text-sm text-gray-400">
                                {{ ($isStaff ?? false) && ! $peer ? 'Pilih customer di inbox kiri untuk mulai membalas.' : 'Belum ada pesan.' }}
                            </p>
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

                        @if (($isStaff ?? false) && ! $peer)
                            <p class="py-3 text-center text-sm text-gray-400">Pilih customer dulu untuk mengirim pesan.</p>
                        @else
                            <form id="chat-form" method="POST" action="{{ route('chat.send') }}" class="flex items-center gap-2 rounded-full border border-gray-200 bg-white px-2 py-2 shadow-sm transition focus-within:ring-2 focus-within:ring-red-400">
                                @if ($isStaff ?? false)
                                    <input type="hidden" id="to_user_id" value="{{ $peer?->id ?? '' }}">
                                @endif

                                <input type="file" id="image-input" accept="image/*" class="hidden">

                                <button type="button" id="attach-btn" class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full text-xl font-semibold leading-none text-gray-400 hover:text-gray-600" aria-label="Lampirkan gambar">
                                    +
                                </button>

                                <span class="h-6 w-px bg-gray-200"></span>

                                <input type="text" id="message" placeholder="Ketik Pesan..." autocomplete="off" aria-label="Ketik pesan" class="min-w-0 flex-1 bg-transparent px-2 py-1.5 text-sm text-gray-700 placeholder-gray-400 focus:outline-none">
                            </form>
                        @endif
                    </div>
                </div>

                @if ($isStaff ?? false)
                    </div>
                @endif

                {{-- Actions --}}
                <div class="mt-6 flex justify-end gap-4">
                    <button type="submit" form="chat-form" id="send-btn" class="rounded-lg bg-green-500 px-10 py-3 font-bold text-white shadow transition hover:bg-green-600 disabled:opacity-50">
                        Kirim
                    </button>
                </div>
            </div>
        </div>
                </div>
            </div>
        </div>

        <x-app-footer />
    </div>

    <script>
        (() => {
            const authId = Number(document.body.dataset.authId);
            const authName = document.body.dataset.authName;
            const threadId = document.body.dataset.thread ? Number(document.body.dataset.thread) : null;
            const isStaff = document.body.dataset.isStaff === '1';
            const peerInput = document.getElementById('to_user_id');
            const peerId = peerInput ? Number(peerInput.value) : null;
            const list = document.getElementById('messages');
            const form = document.getElementById('chat-form');
            if (!list || !form) return;
            const messageInput = document.getElementById('message');
            const imageInput = document.getElementById('image-input');
            const attachBtn = document.getElementById('attach-btn');
            const previewWrap = document.getElementById('image-preview-wrap');
            const previewImg = document.getElementById('image-preview');
            const removeImageBtn = document.getElementById('remove-image');
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
                // Abaikan pesan thread lain (penting buat staff yang buka banyak tab).
                if (data.customer_id && threadId && Number(data.customer_id) !== threadId) return;
                document.getElementById('empty-message')?.remove();
                list.appendChild(bubble({
                    sender: data.sender,
                    senderId: data.sender_id,
                    message: data.message,
                    imageUrl: data.image_url,
                }));
                list.scrollTop = list.scrollHeight;
            }

            // Realtime: satu private channel per customer: `support.{customerId}`.
            // Customer listen ke thread miliknya, staff listen ke thread yang dibuka (?u=).
            window.addEventListener('load', () => {
                if (!window.Echo) {
                    console.warn('Echo not loaded. Run: npm run dev (or build) + php artisan reverb:start');
                    return;
                }
                if (!threadId) return;
                window.Echo.private(`support.${threadId}`).listen('.message.sent', appendMessage);
            });

            form.addEventListener('submit', async (e) => {
                e.preventDefault();

                const text = messageInput.value.trim();
                if (!text && !selectedImage) return;
                if (isStaff && !peerId) {
                    alert('Pilih customer dulu.');
                    return;
                }

                const body = new FormData();
                body.append('message', text);
                if (isStaff && peerId) body.append('to_user_id', String(peerId));
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
                        customer_id: threadId,
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
