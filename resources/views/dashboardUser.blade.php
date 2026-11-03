<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PitStop - Detail Kendaraan</title>
  @vite(['resources/js/app.js'])
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            brand: {
              DEFAULT: '#A31E1E',
              dark: '#7A1414',
              bright: '#DC2626',
              gold: '#C9A227',
            },
          },
          fontFamily: {
            sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
          },
        },
      },
    };
  </script>
</head>

<body class="bg-white font-sans text-gray-900 antialiased"
  data-vehicle-id="{{ $vehicle?->id }}"
  data-status="{{ $vehicle?->status }}">

  <div class="flex min-h-screen flex-col">
    <div class="flex flex-1 flex-col md:flex-row">

      <!-- ============ SIDEBAR ============ -->
      <aside
        class="flex w-full shrink-0 flex-col items-start gap-8 bg-brand px-6 py-6 md:w-64 md:items-stretch md:justify-between md:gap-0 md:px-9 md:py-10">

        {{-- Logo: point this at your own logo file --}}
        <div class="flex justify-center pt-10 pb-8 px-6">
          <img src="{{ asset('images/logo.png') }}" alt="PitStop" class="w-32 h-auto">
        </div>


        <!-- nav space intentionally left empty to match the source screenshot -->
        <div class="hidden flex-1 md:block"></div>

        <!-- Social icons + logout -->
        <div class="flex w-full flex-col gap-6">
          <div class="flex items-center gap-3">
            <!-- REPLACE: social icon — Instagram -->
            <a href="#"
              class="block h-10 w-10 overflow-hidden rounded-full ring-2 ring-white/30 transition hover:ring-white focus-visible:outline-none focus-visible:ring-white">
              <img src="images/instagram.png" alt="Instagram" class="h-full w-full object-cover">
            </a>
            <!-- REPLACE: social icon — Facebook -->
            <a href="#"
              class="block h-10 w-10 overflow-hidden rounded-full ring-2 ring-white/30 transition hover:ring-white focus-visible:outline-none focus-visible:ring-white">
              <img src="images/fb.png" alt="Facebook" class="h-full w-full object-cover">
            </a>
            <!-- REPLACE: social icon — YouTube -->
            <a href="#"
              class="block h-10 w-10 overflow-hidden rounded-full ring-2 ring-white/30 transition hover:ring-white focus-visible:outline-none focus-visible:ring-white">
              <img src="images/yt.png" alt="YouTube" class="h-full w-full object-cover">
            </a>
          </div>

          <form method="POST" action="{{ route('logout') }}" class="w-fit">
            @csrf
            <button type="submit"
              class="flex w-fit items-center justify-center gap-2 rounded-full bg-white px-5 py-2.5 text-sm font-bold text-brand shadow-sm transition hover:bg-gray-50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white md:px-6 md:py-3 md:text-base">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 md:h-5 md:w-5" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                <polyline points="16 17 21 12 16 7"></polyline>
                <line x1="21" y1="12" x2="9" y2="12"></line>
              </svg>
              Keluar
            </button>
          </form>
        </div>
      </aside>

      <!-- ============ MAIN CONTENT ============ -->
      <main class="flex-1 px-6 py-10 sm:px-10 md:px-16 md:py-14">
        <h1 class="text-4xl font-extrabold tracking-tight text-gray-900 sm:text-5xl">{{ $vehicle?->plate_number ?? '-' }}</h1>
        <p class="mt-2 text-base font-semibold text-gray-700 sm:text-lg">{{ auth()->user()->username }}, {{ $vehicle?->vehicle_name ?? '-' }}, {{ $vehicle?->vehicle_type ?? 'Motor' }}</p>

        <!-- Card -->
        <div
          class="mt-10 flex w-full overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-lg shadow-gray-200/60">

          <!-- decorative accent stripe -->
          <div class="flex w-6 shrink-0 sm:w-8">
            <div class="w-1/2 bg-brand-bright"></div>
            <div class="w-1/2 bg-brand-dark"></div>
          </div>

          <div class="flex-1 p-6 sm:p-8 md:p-10">
            <!-- Proportional field list: one grid, one row height, one gap value for every field -->
            <div class="grid grid-cols-1 gap-6">

              <div>
                <p class="mb-2 font-bold text-gray-900">Plat Kendaraan</p>
                <div class="w-full rounded-lg bg-gray-100 px-4 py-3 text-gray-600">{{ $vehicle?->plate_number ?? '-' }}</div>
              </div>

              <div>
                <p class="mb-2 font-bold text-gray-900">Nama Pemilik</p>
                <div class="w-full rounded-lg bg-gray-100 px-4 py-3 text-gray-600">{{ auth()->user()->username }}</div>
              </div>

              <div>
                <p class="mb-2 font-bold text-gray-900">Jenis Kendaraan</p>
                <div class="w-full rounded-lg bg-gray-100 px-4 py-3 text-gray-600">{{ $vehicle?->vehicle_type ?? 'Motor' }}</div>
              </div>

              <div>
                <p class="mb-2 font-bold text-gray-900">Nama Kendaraan</p>
                <div class="w-full rounded-lg bg-gray-100 px-4 py-3 text-gray-600">{{ $vehicle?->vehicle_name ?? '-' }}</div>
              </div>

              <div>
                <p class="mb-2 font-bold text-gray-900">Status</p>
                <div id="status-label" class="w-full rounded-lg bg-gray-100 px-4 py-3 text-gray-600">{{ $vehicle?->statusLabel() ?? '-' }}</div>
              </div>

            </div>

            <div class="mt-10 flex flex-col gap-3 sm:flex-row sm:justify-end">
              <a href="{{ $vehicle ? route('vehicles.edit', $vehicle) : '#' }}"
                class="inline-flex items-center justify-center rounded-lg bg-brand-gold px-6 py-3 font-bold text-white shadow-sm transition hover:brightness-95 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-gold focus-visible:ring-offset-2">
                Ubah Data Kendaraan
              </a>
              <a href="{{ route('chat') }}"
                class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-6 py-3 font-bold text-white shadow-sm transition hover:bg-blue-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-600 focus-visible:ring-offset-2">
                Chat Bengkel
              </a>
            </div>
          </div>
        </div>
      </main>
    </div>

    <!-- ============ FOOTER ============ -->
    <footer class="bg-brand-dark px-6 py-5 text-white">
      <div class="grid grid-cols-1 items-center gap-2 sm:grid-cols-3">
        <div class="hidden sm:block"></div>
        <div class="flex items-center justify-center gap-2 whitespace-nowrap text-center font-semibold">
          <span>&copy;</span>
          <span>PitStop. All Rights Reserved.</span>
        </div>
        <div class="text-center text-xs text-red-200 sm:text-right sm:text-sm">
          V1.0 &nbsp;|&nbsp; Bantuan &nbsp;|&nbsp; Kebijakan Privasi
        </div>
      </div>
    </footer>
  </div>

  {{-- Notifikasi real-time (muncul hanya saat ada pesan / status selesai) --}}
  <div id="realtime-toast"
    class="fixed bottom-4 right-4 z-50 hidden max-w-sm rounded-xl bg-brand px-5 py-4 text-sm font-semibold text-white shadow-xl"></div>

  <script>
    (() => {
      const vehicleId = document.body.dataset.vehicleId;
      const statusLabel = document.getElementById('status-label');
      const toast = document.getElementById('realtime-toast');
      let currentStatus = document.body.dataset.status;
      let toastTimer = null;

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
        } catch (error) {
          console.warn('Audio tidak didukung:', error);
        }
      }

      function showToast(text) {
        toast.textContent = text;
        toast.classList.remove('hidden');
        clearTimeout(toastTimer);
        toastTimer = setTimeout(() => toast.classList.add('hidden'), 8000);
      }

      function handleStatus(data) {
        if (! data || ! data.status) {
          return;
        }

        if (statusLabel) {
          statusLabel.textContent = data.status_label ?? data.status;
        }

        if (data.status === 'selesai' && currentStatus !== 'selesai') {
          beep();
          showToast('Motor ' + (data.plate_number ?? 'kamu') + ' sudah SELESAI diservis. Silakan ambil di bengkel ya!');
        }

        currentStatus = data.status;
      }

      // Realtime via Reverb (butuh `php artisan reverb:start`).
      window.addEventListener('load', () => {
        if (! window.Echo) {
          console.warn('Echo belum termuat. Jalankan npm run build/dev dan reverb:start.');
          return;
        }

        window.Echo.private('App.Models.User.' + {{ auth()->id() }})
          .listen('.message.sent', (data) => {
            beep();
            showToast((data.sender ?? 'Bengkel') + ': ' + data.message);
          });

        if (vehicleId) {
          window.Echo.channel('vehicle.' + vehicleId).listen('.status.updated', handleStatus);
        }
      });

      // Fallback kalau websocket terputus: cek status tiap 15 detik.
      if (vehicleId) {
        setInterval(async () => {
          try {
            const response = await fetch("{{ route('customer.status') }}", {
              headers: { 'Accept': 'application/json' },
            });

            if (response.ok) {
              handleStatus(await response.json());
            }
          } catch (error) {
            console.warn('Polling status gagal:', error);
          }
        }, 15000);
      }
    })();
  </script>

</body>

</html>