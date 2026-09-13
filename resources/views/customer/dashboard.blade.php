<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard Customer - PitStop</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @vite(['resources/js/app.js'])
</head>
<body
    class="min-h-screen bg-white flex flex-col md:flex-row"
    data-vehicle-id="{{ $vehicle?->id }}"
    data-status="{{ $vehicle?->status }}"
>

    {{-- ============ SIDEBAR ============ --}}
    <aside class="md:w-[260px] w-full bg-red-700 flex md:flex-col flex-row items-center md:items-stretch justify-between md:justify-start px-6 md:px-0 py-4 md:py-8">

        {{-- Logo --}}
        <div class="flex md:flex-col items-center md:items-start">
            <div class="flex flex-col items-start">
                <svg width="70" height="70" viewBox="0 0 100 100" class="mb-1 md:ml-6" fill="none">
                    <path d="M35 10 C30 20 25 28 28 40 C22 45 18 55 20 65 L22 90 L35 90 L34 70 C40 72 48 72 55 68 L62 90 L75 90 L68 55 C72 45 70 32 60 22 C52 14 42 12 35 10 Z" fill="white"/>
                    <circle cx="45" cy="30" r="2.2" fill="#7A0E14"/>
                </svg>
                <div class="bg-white text-red-600 font-extrabold text-xs md:text-sm px-3 py-1 md:ml-6 tracking-tight">
                    PITSTOP
                </div>
            </div>
        </div>

        {{-- Nav --}}
        <nav class="flex md:flex-col flex-row items-center gap-3 md:px-6 md:mt-8">
            <a href="{{ route('chat') }}"
               class="text-center rounded-lg py-2.5 px-5 font-bold text-sm bg-white text-red-800 hover:bg-red-50 transition focus:outline-none focus-visible:ring-2 focus-visible:ring-white">
                Chat Bengkel
            </a>
            <form method="POST" action="{{ route('logout') }}" class="md:w-full">
                @csrf
                <button type="submit"
                        class="w-full flex items-center justify-center gap-2 bg-white text-red-800 font-bold rounded-lg px-5 py-2.5 text-sm hover:bg-red-50 transition focus:outline-none focus-visible:ring-2 focus-visible:ring-white">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 3h4a2 2 0 012 2v14a2 2 0 01-2 2h-4"/><path d="M10 17l5-5-5-5"/><path d="M15 12H3"/></svg>
                    Keluar
                </button>
            </form>
        </nav>

        {{-- Social icons (desktop: pushed toward bottom) --}}
        <div class="hidden md:flex flex-col mt-auto mb-6 ml-6 gap-3">
            <div class="flex gap-3">
                <a href="#" aria-label="Instagram" class="w-9 h-9 rounded-full bg-gradient-to-tr from-yellow-400 via-pink-500 to-purple-600 flex items-center justify-center text-white">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.2c3.2 0 3.6 0 4.8.07 1.2.06 2 .24 2.7.5.7.28 1.3.66 1.9 1.26.6.6.98 1.2 1.26 1.9.26.7.44 1.5.5 2.7.07 1.2.07 1.6.07 4.8s0 3.6-.07 4.8c-.06 1.2-.24 2-.5 2.7-.28.7-.66 1.3-1.26 1.9-.6.6-1.2.98-1.9 1.26-.7.26-1.5.44-2.7.5-1.2.07-1.6.07-4.8.07s-3.6 0-4.8-.07c-1.2-.06-2-.24-2.7-.5-.7-.28-1.3-.66-1.9-1.26-.6-.6-.98-1.2-1.26-1.9-.26-.7-.44-1.5-.5-2.7C2.2 15.6 2.2 15.2 2.2 12s0-3.6.07-4.8c.06-1.2.24-2 .5-2.7.28-.7.66-1.3 1.26-1.9.6-.6 1.2-.98 1.9-1.26.7-.26 1.5-.44 2.7-.5C8.4 2.2 8.8 2.2 12 2.2zm0 1.8c-3.15 0-3.52 0-4.76.07-1 .05-1.55.21-1.9.35-.48.19-.82.41-1.18.77-.36.36-.58.7-.77 1.18-.14.35-.3.9-.35 1.9C3 9.28 3 9.65 3 12s0 2.72.07 3.96c.05 1 .21 1.55.35 1.9.19.48.41.82.77 1.18.36.36.7.58 1.18.77.35.14.9.3 1.9.35 1.24.07 1.61.07 4.76.07s3.52 0 4.76-.07c1-.05 1.55-.21 1.9-.35.48-.19.82-.41 1.18-.77.36-.36.58-.7.77-1.18.14-.35.3-.9.35-1.9.07-1.24.07-1.61.07-3.96s0-2.72-.07-3.96c-.05-1-.21-1.55-.35-1.9a3.2 3.2 0 00-.77-1.18 3.2 3.2 0 00-1.18-.77c-.35-.14-.9-.3-1.9-.35C15.52 4 15.15 4 12 4zm0 3.5a4.5 4.5 0 110 9 4.5 4.5 0 010-9zm0 1.8a2.7 2.7 0 100 5.4 2.7 2.7 0 000-5.4zm5.7-2a1.05 1.05 0 11-2.1 0 1.05 1.05 0 012.1 0z"/></svg>
                </a>
                <a href="#" aria-label="Facebook" class="w-9 h-9 rounded-full bg-[#1877F2] flex items-center justify-center text-white">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M13.5 22v-8.4h2.8l.4-3.3h-3.2V8.1c0-.95.27-1.6 1.63-1.6h1.74V3.5c-.3-.04-1.33-.13-2.53-.13-2.5 0-4.2 1.53-4.2 4.33v2.42H7.1v3.3h2.86V22h3.54z"/></svg>
                </a>
                <a href="#" aria-label="YouTube" class="w-9 h-9 rounded-full bg-[#FF0000] flex items-center justify-center text-white">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M22 12s0-3.1-.4-4.6a3 3 0 00-2.1-2.1C17.9 5 12 5 12 5s-5.9 0-7.5.3a3 3 0 00-2.1 2.1C2 8.9 2 12 2 12s0 3.1.4 4.6a3 3 0 002.1 2.1C6.1 19 12 19 12 19s5.9 0 7.5-.3a3 3 0 002.1-2.1C22 15.1 22 12 22 12zM10 15V9l5 3-5 3z"/></svg>
                </a>
            </div>
        </div>
    </aside>

    {{-- ============ MAIN CONTENT ============ --}}
    <main class="flex-1 flex flex-col">
        <div class="flex-1 flex flex-col items-center px-6 py-10 md:py-16">
            <h1 class="text-2xl md:text-4xl font-extrabold text-gray-900 tracking-tight mb-8 md:mb-10 text-center">
                PORTAL&nbsp;&nbsp;SERVIS&nbsp;PITSTOP
            </h1>

            <div class="relative w-full max-w-3xl">
                {{-- Card --}}
                <div class="relative overflow-hidden rounded-2xl bg-black shadow-xl">
                    {{-- gradient accent stripe --}}
                    <div class="absolute inset-y-0 left-0 w-32 bg-gradient-to-r from-red-600 via-red-800 to-red-950 opacity-90"></div>

                    <div class="relative z-10 px-6 sm:px-10 py-10 sm:py-12 flex flex-col gap-4 max-w-md">
                        <p class="text-gray-300 text-xs">
                            Login sebagai: <span class="text-white font-semibold">{{ auth()->user()->username }}</span>
                        </p>

                        @if (session('status'))
                            <div class="rounded-xl bg-green-950/70 border border-green-700 text-green-200 text-sm px-4 py-3">
                                {{ session('status') }}
                            </div>
                        @endif

                        @if ($vehicle)
                            <p class="text-white font-bold text-lg">{{ $vehicle->vehicle_name }}</p>
                            <p class="text-gray-300 text-sm">Plat: <span class="text-white font-semibold">{{ $vehicle->plate_number }}</span></p>
                            <p class="text-gray-300 text-sm">
                                Status:
                                <span id="status-label" class="inline-block rounded-full bg-white/10 border border-white/20 text-white text-xs font-semibold px-3 py-1">
                                    {{ $vehicle->statusLabel() }}
                                </span>
                            </p>
                            <p class="text-gray-400 text-xs leading-relaxed">
                                Status diperbarui otomatis. Tunggu notifikasi saat motor selesai.
                            </p>
                        @else
                            <p class="text-gray-300 text-sm leading-relaxed">
                                Belum ada motor tertaut ke akun ini.
                            </p>
                            <a href="{{ route('vehicle.claim') }}"
                               class="text-center bg-red-600 hover:bg-red-700 transition text-white font-bold py-4 rounded-xl text-sm sm:text-base">
                                Klaim Motor
                            </a>
                        @endif
                    </div>
                </div>

                {{-- Popup saat motor selesai --}}
                <div id="done-popup" style="display:none" class="mt-6 rounded-2xl bg-green-50 border border-green-300 text-green-800 px-5 py-4 shadow-sm">
                    <p class="font-bold">Motor sudah selesai!</p>
                    <p class="text-sm mt-1">Silakan ambil motor Anda di bengkel.</p>
                    <button type="button" id="popup-close" class="mt-3 rounded-lg bg-green-600 hover:bg-green-700 text-white text-sm font-semibold px-4 py-2 transition">
                        Tutup
                    </button>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <footer class="bg-red-950 text-white text-xs sm:text-sm">
            <div class="max-w-6xl mx-auto px-6 py-4 flex flex-col sm:flex-row items-center justify-between gap-2">
                <span class="font-semibold">&copy; PitStop. All Rights Reserved.</span>
                <span class="text-gray-200">V1.0 | Bantuan | Kebijakan Privasi</span>
            </div>
        </footer>
    </main>

    <script>
        const vehicleId = document.body.dataset.vehicleId;
        const statusLabel = document.getElementById('status-label');
        const popup = document.getElementById('done-popup');
        const popupClose = document.getElementById('popup-close');

        if (popupClose) {
            popupClose.addEventListener('click', () => {
                popup.style.display = 'none';
            });
        }

        let currentStatus = document.body.dataset.status;

        // Bunyi notifikasi pakai Web Audio API (tanpa file suara).
        function beep() {
            try {
                const ctx = new (window.AudioContext || window.webkitAudioContext)();
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();
                osc.connect(gain);
                gain.connect(ctx.destination);
                osc.frequency.value = 880;
                osc.type = 'sine';
                gain.gain.setValueAtTime(0.3, ctx.currentTime);
                osc.start();
                osc.stop(ctx.currentTime + 0.6);
            } catch (e) {
                console.warn('Audio tidak didukung:', e);
            }
        }

        function handleStatus(data) {
            if (!data || !data.status) {
                return;
            }

            if (statusLabel) {
                statusLabel.textContent = data.status_label ?? data.status;
            }

            if (data.status === 'selesai' && currentStatus !== 'selesai') {
                beep();
                popup.style.display = 'block';
            }

            currentStatus = data.status;
        }

        // 1) Realtime via Reverb (butuh `php artisan reverb:start`).
        window.addEventListener('load', () => {
            if (!window.Echo || !vehicleId) {
                return;
            }
            window.Echo.channel('vehicle.' + vehicleId).listen('.status.updated', handleStatus);
        });

        // 2) Fallback polling tiap 10 detik kalau websocket terputus.
        setInterval(async () => {
            try {
                const res = await fetch("{{ route('customer.status') }}", {
                    headers: { 'Accept': 'application/json' },
                });
                if (res.ok) {
                    handleStatus(await res.json());
                }
            } catch (e) {
                console.warn('Polling status gagal:', e);
            }
        }, 10000);
    </script>

</body>
</html>
