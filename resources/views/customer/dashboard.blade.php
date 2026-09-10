<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard Customer</title>
    @vite(['resources/js/app.js'])
</head>
<body
    data-vehicle-id="{{ $vehicle?->id }}"
    data-status="{{ $vehicle?->status }}"
>

    <h1>Dashboard Customer</h1>
    <p>Login sebagai: {{ auth()->user()->username }}</p>

    <nav>
        <a href="{{ route('chat') }}">Chat Bengkel</a>
        <form method="POST" action="{{ route('logout') }}" style="display:inline">
            @csrf
            <button type="submit">Logout</button>
        </form>
    </nav>

    <hr>

    @if (session('status'))
        <p>{{ session('status') }}</p>
    @endif

    @if ($vehicle)
        <h2>{{ $vehicle->vehicle_name }} ({{ $vehicle->plate_number }})</h2>
        <p>Status: <strong id="status-label">{{ $vehicle->statusLabel() }}</strong></p>
    @else
        <p>Belum ada motor tertaut. <a href="{{ route('vehicle.claim') }}">Klaim motor</a></p>
    @endif

    {{-- Popup saat motor selesai --}}
    <div id="done-popup" style="display:none; border:2px solid green; padding:16px; margin-top:16px;">
        <strong>Motor sudah selesai!</strong>
        <p>Silakan ambil motor Anda di bengkel.</p>
        <button type="button" id="popup-close">Tutup</button>
    </div>

    <script>
        const vehicleId = document.body.dataset.vehicleId;
        const statusLabel = document.getElementById('status-label');
        const popup = document.getElementById('done-popup');
        document.getElementById('popup-close').addEventListener('click', () => {
            popup.style.display = 'none';
        });

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

            statusLabel.textContent = data.status_label ?? data.status;

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
