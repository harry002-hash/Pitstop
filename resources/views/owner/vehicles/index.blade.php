<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Bengkel - PitStop</title>

    {{-- Quick-start Tailwind via CDN so this renders immediately. If Tailwind is already
         compiled through Vite in this project, delete this line, use @vite(...) as usual,
         and make sure this file's path is covered by tailwind.config.js "content". --}}
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">

<div class="min-h-screen flex flex-col">
    <div class="flex flex-1">

        {{-- ============ SIDEBAR ============ --}}
        <aside class="w-64 shrink-0 bg-red-900 flex flex-col">

            {{-- Logo: point this at your own logo file --}}
            <div class="flex justify-center pt-10 pb-8 px-6">
                <img src="{{ asset('images/logo.png') }}" alt="PitStop" class="w-32 h-auto">
            </div>

            {{-- Route names below are placeholders - rename to match your actual routes --}}
            <nav class="px-6 flex flex-col gap-3">
                <a href="{{ route('owner.dashboard') }}"
                   class="text-center rounded-lg py-3 font-bold transition focus:outline-none focus-visible:ring-2 focus-visible:ring-white
                          {{ request()->routeIs('owner.dashboard')
                                ? 'bg-red-600 text-white'
                                : 'bg-white text-red-800 hover:bg-red-50' }}">
                    Halaman Utama
                </a>
                <a href="{{ route('owner.vehicles.index') }}"
                   class="text-center rounded-lg py-3 font-bold transition focus:outline-none focus-visible:ring-2 focus-visible:ring-white
                          {{ request()->routeIs('owner.vehicles.*')
                                ? 'bg-red-600 text-white'
                                : 'bg-white text-red-800 hover:bg-red-50' }}">
                    Daftar Motor
                </a>
            </nav>

            <div class="flex-1"></div>

            {{-- Social icons: point each src at your own icon file --}}
            <div class="flex justify-center gap-4 pb-8">
                <a href="#" target="_blank" rel="noopener" aria-label="Instagram">
                    <img src="{{ asset('images/instagram.png') }}" alt="Instagram" class="w-9 h-9 rounded-full">
                </a>
                <a href="#" target="_blank" rel="noopener" aria-label="Facebook">
                    <img src="{{ asset('images/fb.png') }}" alt="Facebook" class="w-9 h-9 rounded-full">
                </a>
                <a href="#" target="_blank" rel="noopener" aria-label="YouTube">
                    <img src="{{ asset('images/yt.png') }}" alt="YouTube" class="w-9 h-9 rounded-full">
                </a>
            </div>

            <div class="px-6 pb-10">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="w-full flex items-center justify-center gap-2 bg-white text-red-800 font-bold rounded-lg py-3 hover:bg-red-50 transition focus:outline-none focus-visible:ring-2 focus-visible:ring-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m0-8H5a2 2 0 00-2 2v12a2 2 0 002 2h2" />
                        </svg>
                        Keluar
                    </button>
                </form>
            </div>
        </aside>

        {{-- ============ MAIN CONTENT ============ --}}
        <main class="flex-1 p-8">

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
                                            <a href="{{ route('owner.vehicles.edit', $vehicle) }}"
                                               class="rounded-md border border-blue-500 text-blue-600 text-xs font-semibold px-3 py-1.5 hover:bg-blue-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500">
                                                Ubah
                                            </a>
                                            {{-- Notification/reminder action - wire this up to whatever it should trigger --}}
                                            <button type="button" aria-label="Kirim pengingat"
                                                    class="w-8 h-8 flex items-center justify-center rounded-full border border-gray-400 text-gray-600 hover:bg-gray-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-gray-500">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2a2 2 0 01-.6 1.4L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-10 text-center text-gray-500">
                                        Belum ada motor. Klik "Daftar Motor" untuk menambahkan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    {{-- ============ FOOTER ============ --}}
    <footer class="bg-red-900 text-white">
        <div class="grid grid-cols-3 items-center px-8 py-4">
            <div></div>
            <p class="text-center font-semibold">&copy; PitStop. All Rights Reserved.</p>
            <p class="text-right text-xs text-red-200">V1.0 | Bantuan | Kebijakan Privasi</p>
        </div>
    </footer>
</div>

</body>
</html>
