<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard Bengkel - PitStop</title>

    {{-- Quick-start Tailwind via CDN so this renders immediately. If Tailwind is already
         compiled through Vite in this project, delete this line, use @vite(...) as usual,
         and make sure this file's path is covered by tailwind.config.js "content". --}}
    <script src="https://cdn.tailwindcss.com"></script>
    @vite(['resources/js/app.js'])
</head>
<body class="bg-gray-100 text-gray-800">

<div class="min-h-screen flex flex-col">
    <div class="flex flex-1">

        {{-- ============ SIDEBAR ============ --}}
        <x-app-sidebar>
            <x-slot:nav>
                <x-sidebar-link :href="route('owner.dashboard')" :active="request()->routeIs('owner.dashboard')">
                    Halaman Utama
                </x-sidebar-link>
                <x-sidebar-link :href="route('chat')" :active="request()->routeIs('chat')">
                    Chat
                </x-sidebar-link>
            </x-slot:nav>
        </x-app-sidebar>

        {{-- ============ MAIN CONTENT ============ --}}
        <main class="flex-1 p-8">

            {{-- Topbar: lonceng notifikasi --}}
            <div class="mb-6 flex items-center justify-end">
                <div class="relative">
                    <button type="button" id="notif-bell" aria-label="Notifikasi"
                            class="relative flex h-11 w-11 items-center justify-center rounded-full border border-gray-200 bg-white text-gray-600 shadow-sm transition hover:bg-gray-50 hover:text-red-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2a2 2 0 01-.6 1.4L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <span id="notif-badge" class="absolute -right-1 -top-1 hidden min-h-5 min-w-5 items-center justify-center rounded-full bg-red-600 px-1 text-[11px] font-bold text-white">0</span>
                    </button>

                    <div id="notif-panel" class="absolute right-0 z-50 mt-2 hidden w-80 max-w-[85vw] overflow-hidden rounded-xl border border-gray-100 bg-white shadow-xl">
                        <div class="flex items-center justify-between border-b border-gray-100 px-4 py-3">
                            <p class="text-sm font-bold">Notifikasi</p>
                            <button type="button" id="notif-read" class="text-xs font-semibold text-red-600 hover:underline">Tandai dibaca</button>
                        </div>
                        <div id="notif-list" class="max-h-80 overflow-y-auto">
                            <p class="px-4 py-6 text-center text-sm text-gray-400">Memuat...</p>
                        </div>
                    </div>
                </div>
            </div>

            @if (session('status'))
                <div class="mb-6 rounded-lg bg-green-50 border border-green-200 text-green-700 px-4 py-3">
                    {{ session('status') }}
                </div>
            @endif

            {{-- Stat cards - pass real counts from the controller; falling back to 0
                 so the view doesn't break if a variable isn't sent yet. --}}
            <div class="flex flex-wrap gap-6 justify-between mb-8">

                <div class="flex-1 min-w-[220px] max-w-xs bg-white rounded-xl shadow-sm flex overflow-hidden">
                    <div class="w-4 flex shrink-0">
                        <div class="w-1/2 bg-red-600"></div>
                        <div class="w-1/2 bg-red-900"></div>
                    </div>
                    <div class="flex-1 text-center py-6 px-4">
                        <p class="font-bold">Antrian Tunggu</p>
                        <p class="font-bold text-xl my-1">{{ $antrianTungguCount ?? 0 }}</p>
                        <p class="font-bold">Kendaraan</p>
                    </div>
                </div>

                <div class="flex-1 min-w-[220px] max-w-xs bg-white rounded-xl shadow-sm flex overflow-hidden">
                    <div class="w-4 flex shrink-0">
                        <div class="w-1/2 bg-red-600"></div>
                        <div class="w-1/2 bg-red-900"></div>
                    </div>
                    <div class="flex-1 text-center py-6 px-4">
                        <p class="font-bold">Proses Pengerjaan</p>
                        <p class="font-bold text-xl my-1">{{ $prosesPengerjaanCount ?? 0 }}</p>
                        <p class="font-bold">Kendaraan</p>
                    </div>
                </div>

                {{-- Screenshot repeats "Proses Pengerjaan" here too - probably meant to be
                     a 3rd status (e.g. "Selesai"). Kept the label as shown but gave it its
                     own variable so it's a one-line change once confirmed. --}}
                <div class="flex-1 min-w-[220px] max-w-xs bg-white rounded-xl shadow-sm flex overflow-hidden">
                    <div class="w-4 flex shrink-0">
                        <div class="w-1/2 bg-red-600"></div>
                        <div class="w-1/2 bg-red-900"></div>
                    </div>
                    <div class="flex-1 text-center py-6 px-4">
                        <p class="font-bold">Proses Pengerjaan</p>
                        <p class="font-bold text-xl my-1">{{ $thirdCardCount ?? 0 }}</p>
                        <p class="font-bold">Kendaraan</p>
                    </div>
                </div>
            </div>

            {{-- Search + filter (UI only - not wired to a query yet) --}}
            <div class="flex flex-wrap items-center gap-4 mb-6">
                <div class="relative w-full sm:w-64">
                    <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m1.35-5.15a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </span>
                    <input type="text" name="search" placeholder="Cari Plat Nomor..." aria-label="Cari plat nomor"
                           class="w-full rounded-lg border border-gray-300 pl-9 pr-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
                </div>

                <button type="button"
                        class="flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 4h18l-7 8v6l-4 2v-8L3 4z" />
                    </svg>
                    Filter
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <button type="button" id="open-generate-modal"
                   class="ml-auto rounded-lg bg-red-600 px-5 py-2 text-sm font-bold text-white hover:bg-red-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-600">
                    + Generate Akun
                </button>
            </div>

            {{-- Table --}}
            <div class="bg-white rounded-xl shadow-sm flex overflow-hidden">
                <div class="w-4 flex shrink-0">
                    <div class="w-1/2 bg-red-600"></div>
                    <div class="w-1/2 bg-red-900"></div>
                </div>

                <div class="flex-1 overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="text-left font-bold py-4 px-4">No</th>
                                <th class="text-left font-bold py-4 px-4">Plat Kendaraan</th>
                                <th class="text-left font-bold py-4 px-4">Nama Pemilik</th>
                                <th class="text-left font-bold py-4 px-4">Jenis Kendaraan</th>
                                <th class="text-center font-bold py-4 px-4">Status</th>
                                <th class="text-left font-bold py-4 px-4">Nama Kendaraan</th>
                                <th class="text-center font-bold py-4 px-4">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($vehicles as $vehicle)
                                @php
                                    $label = $vehicle->statusLabel();
                                    $badgeClasses = match(true) {
                                        str_contains($label, 'Selesai') => 'bg-green-50 text-green-600 border border-green-300',
                                        str_contains($label, 'Belum')   => 'bg-yellow-50 text-yellow-600 border border-yellow-300',
                                        default                          => 'bg-orange-50 text-orange-600 border border-orange-300',
                                    };
                                @endphp
                                <tr class="border-b border-gray-100 last:border-b-0">
                                    <td class="py-4 px-4">{{ $loop->iteration }}.</td>
                                    <td class="py-4 px-4">{{ $vehicle->plate_number }}</td>
                                    <td class="py-4 px-4">{{ $vehicle->owner?->username ?? '— (belum diklaim)' }}</td>
                                    {{-- "Jenis Kendaraan" isn't in the original code - add this
                                         column/attribute to your vehicles table if it's missing. --}}
                                    <td class="py-4 px-4">{{ $vehicle->vehicle_type ?? '-' }}</td>
                                    <td class="py-4 px-4 text-center">
                                        <span class="inline-block rounded-full text-xs font-semibold px-3 py-1 whitespace-nowrap {{ $badgeClasses }}">
                                            {{ $label }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-4">{{ $vehicle->vehicle_name }}</td>
                                    <td class="py-4 px-4">
                                        <div class="flex items-center justify-center gap-2">
                                            <button type="button" data-edit-open="{{ $vehicle->id }}"
                                               class="rounded-md border border-blue-500 text-blue-600 text-xs font-semibold px-3 py-1.5 hover:bg-blue-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500">
                                                Ubah
                                            </button>
                                            {{-- Kirim pengingat ke customer pemilik motor --}}
                                            <button type="button" aria-label="Kirim pengingat" title="Kirim pengingat ke {{ $vehicle->owner?->username ?? 'customer' }}"
                                                    data-remind-url="{{ route('owner.vehicles.remind', $vehicle) }}"
                                                    data-remind-to="{{ $vehicle->owner?->username ?? '' }}"
                                                    class="flex h-8 w-8 items-center justify-center rounded-full border border-gray-400 text-gray-600 transition hover:bg-gray-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-gray-500 disabled:opacity-50">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2a2 2 0 01-.6 1.4L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-10 text-center text-gray-500">
                                        Belum ada akun. Klik "Generate Akun" untuk menambahkan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- ============ EDIT MODALS (satu per baris) ============ --}}
            @foreach ($vehicles as $vehicle)
                <div id="edit-modal-{{ $vehicle->id }}" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4">
                    <div class="w-full max-w-md overflow-hidden rounded-2xl bg-white shadow-2xl" role="dialog" aria-modal="true" aria-labelledby="edit-modal-title-{{ $vehicle->id }}">
                        <div class="bg-blue-600 px-6 py-4">
                            <h2 id="edit-modal-title-{{ $vehicle->id }}" class="font-bold text-white">Ubah Data: {{ $vehicle->plate_number }}</h2>
                            <p class="mt-1 text-sm text-blue-100">Kosongkan password kalau tidak diganti.</p>
                        </div>

                        <form method="POST" action="{{ route('owner.vehicles.update', $vehicle) }}" class="space-y-4 px-6 py-6">
                            @csrf
                            @method('PUT')

                            @if ($errors->any() && (int) session('edit_id') === (int) $vehicle->id)
                                <ul class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            @endif

                            <div>
                                <label for="edit-vehicle_name-{{ $vehicle->id }}" class="mb-1 block text-sm font-semibold">Nama motor</label>
                                <input type="text" id="edit-vehicle_name-{{ $vehicle->id }}" name="vehicle_name" value="{{ old('vehicle_name', $vehicle->vehicle_name) }}" required maxlength="100"
                                       class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-600">
                            </div>

                            <div>
                                <label for="edit-plate_number-{{ $vehicle->id }}" class="mb-1 block text-sm font-semibold">Plat motor</label>
                                <input type="text" id="edit-plate_number-{{ $vehicle->id }}" name="plate_number" value="{{ old('plate_number', $vehicle->plate_number) }}" required maxlength="20"
                                       class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-600">
                            </div>

                            <div>
                                <label for="edit-plate_password-{{ $vehicle->id }}" class="mb-1 block text-sm font-semibold">Password baru</label>
                                <input type="text" id="edit-plate_password-{{ $vehicle->id }}" name="plate_password" minlength="4" maxlength="50" autocomplete="off" placeholder="Kosongkan kalau tidak diganti"
                                       class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-600">
                            </div>

                            <div>
                                <label for="edit-status-{{ $vehicle->id }}" class="mb-1 block text-sm font-semibold">Status</label>
                                <select id="edit-status-{{ $vehicle->id }}" name="status" required
                                        class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-600">
                                    @foreach ($statuses as $value => $label)
                                        <option value="{{ $value }}" @selected(old('status', $vehicle->status) === $value)>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="flex justify-end gap-3 pt-2">
                                <button type="button" data-edit-close
                                        class="rounded-lg border border-gray-300 px-5 py-2 text-sm font-bold text-gray-700 hover:bg-gray-50">
                                    Batal
                                </button>
                                <button type="submit"
                                        class="rounded-lg bg-blue-600 px-5 py-2 text-sm font-bold text-white hover:bg-blue-700">
                                    Perbarui
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endforeach
        </main>
    </div>

    {{-- ============ FOOTER ============ --}}
    <x-app-footer />
</div>

{{-- Toast popup + bunyi (lonceng tetap untuk riwayat; bunyi cukup sekali dari sini) --}}
<x-toast-stack
    :fetch-url="route('owner.notifications')"
    channel="staff.alerts"
    eventName=".staff.alert"
    mode="alert"
/>

{{-- ============ GENERATE AKUN MODAL ============ --}}
<div id="generate-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4">
    <div class="w-full max-w-md overflow-hidden rounded-2xl bg-white shadow-2xl" role="dialog" aria-modal="true" aria-labelledby="generate-modal-title">
        <div class="bg-red-600 px-6 py-4">
            <h2 id="generate-modal-title" class="font-bold text-white">Generate Akun</h2>
            <p class="mt-1 text-sm text-red-100">Masukkan plat + password, lalu berikan password ke customer supaya bisa klaim.</p>
        </div>

        <form method="POST" action="{{ route('owner.vehicles.store') }}" class="space-y-4 px-6 py-6">
            @csrf

            @if ($errors->any())
                <ul class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif

            <div>
                <label for="modal-vehicle_name" class="mb-1 block text-sm font-semibold">Nama motor</label>
                <input type="text" id="modal-vehicle_name" name="vehicle_name" value="{{ old('vehicle_name') }}" required maxlength="100" placeholder="cth: Vario 125"
                       class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
            </div>

            <div>
                <label for="modal-plate_number" class="mb-1 block text-sm font-semibold">Plat motor</label>
                <input type="text" id="modal-plate_number" name="plate_number" value="{{ old('plate_number') }}" required maxlength="20" placeholder="cth: KB 1234 AB"
                       class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
            </div>

            <div>
                <label for="modal-plate_password" class="mb-1 block text-sm font-semibold">Password buat plat</label>
                <input type="text" id="modal-plate_password" name="plate_password" required minlength="4" maxlength="50" placeholder="min. 4 karakter"
                       class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
            </div>

            <div>
                <label for="modal-status" class="mb-1 block text-sm font-semibold">Status</label>
                <select id="modal-status" name="status" required
                        class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
                    @foreach ($statuses as $value => $label)
                        <option value="{{ $value }}" @selected(old('status') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <button type="button" id="close-generate-modal"
                        class="rounded-lg border border-gray-300 px-5 py-2 text-sm font-bold text-gray-700 hover:bg-gray-50">
                    Batal
                </button>
                <button type="submit"
                        class="rounded-lg bg-red-600 px-5 py-2 text-sm font-bold text-white hover:bg-red-700">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    (() => {
        const modal = document.getElementById('generate-modal');
        const openBtn = document.getElementById('open-generate-modal');
        const closeBtn = document.getElementById('close-generate-modal');

        function openModal() {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.getElementById('modal-plate_number')?.focus();
        }

        function closeModal() {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        openBtn?.addEventListener('click', openModal);
        closeBtn?.addEventListener('click', closeModal);

        modal?.addEventListener('click', (e) => {
            if (e.target === modal) closeModal();
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !modal.classList.contains('hidden')) closeModal();
        });

        @if ($errors->any() && ! session('edit_id'))
            openModal();
        @endif

        // ---- Edit modal per baris ----
        function openEditModal(id) {
            const m = document.getElementById(`edit-modal-${id}`);
            if (!m) return;
            m.classList.remove('hidden');
            m.classList.add('flex');
        }

        function closeEditModal(m) {
            m.classList.add('hidden');
            m.classList.remove('flex');
        }

        document.querySelectorAll('[data-edit-open]').forEach((btn) => {
            btn.addEventListener('click', () => openEditModal(btn.dataset.editOpen));
        });

        document.querySelectorAll('[id^="edit-modal-"]').forEach((m) => {
            m.addEventListener('click', (e) => {
                if (e.target === m || e.target.closest('[data-edit-close]')) closeEditModal(m);
            });
        });

        document.addEventListener('keydown', (e) => {
            if (e.key !== 'Escape') return;
            document.querySelectorAll('[id^="edit-modal-"]').forEach((m) => {
                if (!m.classList.contains('hidden')) closeEditModal(m);
            });
        });

        @if (session('edit_id'))
            openEditModal({{ (int) session('edit_id') }});
        @endif
    })();
</script>

<script>
    // Tombol lonceng per baris: kirim pengingat ke customer pemilik motor.
    (() => {
        const csrf = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
        const checkSvg = '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>';

        document.querySelectorAll('[data-remind-url]').forEach((btn) => {
            const original = btn.innerHTML;
            btn.addEventListener('click', async () => {
                if (btn.disabled) return;
                if (!btn.dataset.remindTo) {
                    alert('Motor ini belum diklaim customer.');
                    return;
                }
                btn.disabled = true;
                try {
                    const res = await fetch(btn.dataset.remindUrl, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrf,
                            'Accept': 'application/json',
                            ...(window.Echo?.socketId()
                                ? { 'X-Socket-ID': window.Echo.socketId() }
                                : {}),
                        },
                    });
                    const data = await res.json().catch(() => ({}));
                    if (!res.ok || !data.success) {
                        alert(data.message ?? 'Gagal mengirim pengingat.');
                        btn.disabled = false;
                        return;
                    }
                    btn.classList.remove('border-gray-400', 'text-gray-600');
                    btn.classList.add('border-green-500', 'text-green-600', 'bg-green-50');
                    btn.innerHTML = checkSvg;
                    setTimeout(() => {
                        btn.classList.add('border-gray-400', 'text-gray-600');
                        btn.classList.remove('border-green-500', 'text-green-600', 'bg-green-50');
                        btn.innerHTML = original;
                        btn.disabled = false;
                    }, 2500);
                } catch (e) {
                    alert('Gagal mengirim pengingat. Periksa koneksi Anda.');
                    btn.disabled = false;
                }
            });
        });
    })();
</script>

<script>
    (() => {
        const bell = document.getElementById('notif-bell');
        const panel = document.getElementById('notif-panel');
        const badge = document.getElementById('notif-badge');
        const list = document.getElementById('notif-list');
        const readBtn = document.getElementById('notif-read');
        if (!bell || !panel || !list) return;

        const seen = new Set();
        let unread = 0;
        let firstLoad = true;

        function esc(s) {
            return String(s ?? '').replace(/[&<>"']/g, (c) => ({
                '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;',
            }[c]));
        }

        function renderBadge() {
            badge.textContent = unread > 9 ? '9+' : String(unread);
            badge.classList.toggle('hidden', unread === 0);
            badge.classList.toggle('flex', unread > 0);
        }

        function renderEmpty() {
            list.innerHTML = '<p class="px-4 py-6 text-center text-sm text-gray-400">Belum ada notifikasi.</p>';
        }

        function prependItem(n) {
            list.querySelector('p')?.remove();
            const dot = n.type === 'chat' ? 'bg-blue-500' : 'bg-yellow-500';
            const a = document.createElement('a');
            a.href = n.url || '#';
            a.className = 'flex gap-3 border-b border-gray-50 px-4 py-3 transition hover:bg-gray-50 last:border-b-0';
            a.innerHTML =
                `<span class="mt-1.5 h-2.5 w-2.5 shrink-0 rounded-full ${dot}"></span>` +
                `<span class="min-w-0">` +
                `<span class="block truncate text-sm font-bold text-gray-900">${esc(n.title)}</span>` +
                `<span class="block truncate text-xs text-gray-600">${esc(n.body)}</span>` +
                `<span class="mt-0.5 block text-[11px] text-gray-400">${esc(n.time)}</span>` +
                `</span>`;
            list.prepend(a);
            while (list.children.length > 10) list.lastChild.remove();
        }

        // true = item baru (belum pernah terlihat) -> bunyi + badge.
        function ingest(items, silent) {
            let fresh = 0;
            [...items].reverse().forEach((n) => {
                if (!n.key || seen.has(n.key)) return;
                seen.add(n.key);
                prependItem(n);
                if (!silent) fresh++;
            });
            if (fresh > 0) {
                unread += fresh;
                renderBadge();
            }
        }

        async function poll(silent) {
            try {
                const res = await fetch("{{ route('owner.notifications') }}", {
                    headers: { 'Accept': 'application/json' },
                });
                if (!res.ok) return;
                const data = await res.json();
                if (list.querySelector('p') && (data.notifications ?? []).length === 0 && firstLoad) renderEmpty();
                ingest(data.notifications ?? [], silent);
            } catch (e) { /* offline, coba lagi next poll */ }
            firstLoad = false;
        }

        function setOpen(open) {
            panel.classList.toggle('hidden', !open);
            if (open) {
                unread = 0;
                renderBadge();
            }
        }

        bell.addEventListener('click', (e) => {
            e.stopPropagation();
            setOpen(panel.classList.contains('hidden'));
        });

        document.addEventListener('click', (e) => {
            if (!panel.classList.contains('hidden') && !panel.contains(e.target)) setOpen(false);
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') setOpen(false);
        });

        readBtn.addEventListener('click', () => {
            unread = 0;
            renderBadge();
        });

        void poll(true);
        setInterval(() => void poll(false), 15000);

        // Realtime (kalau Reverb jalan): notif instan + bunyi.
        window.addEventListener('load', () => {
            if (!window.Echo) return;
            try {
                window.Echo.private('staff.alerts').listen('.staff.alert', (n) => ingest([n], false));
            } catch (e) { /* polling tetap jalan sebagai fallback */ }
        });
    })();
</script>

</body>
</html>
