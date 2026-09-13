<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>PitStop — Detail Kendaraan</title>
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
@vite(['resources/js/app.js'])
</head>
<body class="bg-white font-sans text-gray-900 antialiased">

<div class="flex min-h-screen flex-col">
  <div class="flex flex-1 flex-col md:flex-row">

    <x-app-sidebar>
      <x-slot:nav>
        <x-sidebar-link :href="route('customer.dashboard')" :active="request()->routeIs('customer.dashboard')">Dashboard</x-sidebar-link>
        <x-sidebar-link :href="route('chat')" :active="request()->routeIs('chat')">Chat Bengkel</x-sidebar-link>
      </x-slot:nav>
    </x-app-sidebar>

    <!-- ============ MAIN CONTENT ============ -->
    <main class="flex-1 px-5 py-6 sm:px-8 md:px-10 md:py-8">
      @if (session('status'))
        <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-2.5 text-sm text-green-700">
          {{ session('status') }}
        </div>
      @endif

      {{-- Banner gede: motor selesai, susah kelewat --}}
      @if ($vehicle && $vehicle->status === \App\Models\Vehicle::STATUS_SELESAI)
        <div id="done-banner" class="mb-4 overflow-hidden rounded-2xl bg-green-600 text-white shadow-lg">
          <div class="flex items-center gap-4 px-5 py-4">
            <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-white/20 text-2xl">🎉</span>
            <div class="min-w-0 flex-1">
              <p class="text-lg font-extrabold leading-tight">Motor kamu sudah SELESAI!</p>
              <p class="truncate text-sm text-green-100">{{ $vehicle->plate_number }} — {{ $vehicle->vehicle_name }} siap diambil di bengkel.</p>
            </div>
            <a href="{{ route('chat') }}" class="shrink-0 rounded-lg bg-white px-4 py-2 text-sm font-bold text-green-700 shadow-sm transition hover:bg-green-50">
              Chat Bengkel
            </a>
            <button type="button" id="done-banner-close" aria-label="Tutup" class="shrink-0 rounded-full px-2 text-2xl leading-none text-green-200 transition hover:text-white">&times;</button>
          </div>
        </div>
        <script>
          (() => {
            const banner = document.getElementById('done-banner');
            const key = 'done-banner-seen-{{ $vehicle->id }}-{{ $vehicle->updated_at->timestamp }}';
            if (sessionStorage.getItem(key)) banner?.remove();
            document.getElementById('done-banner-close')?.addEventListener('click', () => {
              sessionStorage.setItem(key, '1');
              banner?.remove();
            });
          })();
        </script>
      @endif

      @if ($vehicle)
        <h1 class="text-2xl font-extrabold tracking-tight text-gray-900 sm:text-3xl">{{ $vehicle->plate_number }}</h1>
        <p class="mt-1 text-sm font-semibold text-gray-700 sm:text-base">{{ auth()->user()->username }}, {{ $vehicle->vehicle_name }}, Motor</p>

        <!-- Card -->
        <div class="mt-6 flex w-full overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-lg shadow-gray-200/60">

          <!-- decorative accent stripe -->
          <div class="flex w-5 shrink-0 sm:w-6">
            <div class="w-1/2 bg-brand-bright"></div>
            <div class="w-1/2 bg-brand-dark"></div>
          </div>

          <div class="flex-1 p-5 sm:p-6">
            <!-- Proportional field list: one grid, one row height, one gap value for every field -->
            <div class="grid grid-cols-1 gap-4">

              <div>
                <p class="mb-1.5 font-bold text-gray-900">Plat Kendaraan</p>
                <div class="w-full rounded-lg bg-gray-100 px-4 py-2.5 text-gray-600">{{ $vehicle->plate_number }}</div>
              </div>

              <div>
                <p class="mb-1.5 font-bold text-gray-900">Nama Pemilik</p>
                <div class="w-full rounded-lg bg-gray-100 px-4 py-2.5 text-gray-600">{{ auth()->user()->username }}</div>
              </div>

              <div>
                <p class="mb-1.5 font-bold text-gray-900">Jenis Kendaraan</p>
                <div class="w-full rounded-lg bg-gray-100 px-4 py-2.5 text-gray-600">Motor</div>
              </div>

              <div>
                <p class="mb-1.5 font-bold text-gray-900">Nama Kendaraan</p>
                <div class="w-full rounded-lg bg-gray-100 px-4 py-2.5 text-gray-600">{{ $vehicle->vehicle_name }}</div>
              </div>

              <div>
                <p class="mb-1.5 font-bold text-gray-900">Status</p>
                <div class="w-full rounded-lg bg-gray-100 px-4 py-2.5 text-gray-600">{{ $vehicle->statusLabel() }}</div>
              </div>

            </div>

            <div class="mt-6 flex flex-col gap-2.5 sm:flex-row sm:justify-end">
              <a href="{{ route('customer.vehicle.edit') }}" class="rounded-lg bg-brand-gold px-6 py-2.5 text-center font-bold text-white shadow-sm transition hover:brightness-95 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-gold focus-visible:ring-offset-2">
                Ubah Data Kendaraan
              </a>
              <a href="{{ route('chat') }}" class="rounded-lg bg-blue-600 px-6 py-2.5 text-center font-bold text-white shadow-sm transition hover:bg-blue-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-600 focus-visible:ring-offset-2">
                Chat Bengkel
              </a>
            </div>
          </div>
        </div>
      @else
        <div class="mt-6 rounded-2xl border border-gray-100 bg-white p-6 text-center shadow-lg shadow-gray-200/60">
          <h1 class="text-2xl font-extrabold tracking-tight text-gray-900">Belum ada kendaraan</h1>
          <p class="mt-1 text-sm text-gray-600">Klaim motor kamu dulu pakai plat + password dari bengkel.</p>
          <a href="{{ route('vehicle.claim') }}" class="mt-4 inline-block rounded-lg bg-red-600 px-6 py-2.5 font-bold text-white shadow-sm transition hover:bg-red-700">
            Klaim Motor
          </a>
        </div>
      @endif
    </main>
  </div>

  <x-app-footer />
</div>

{{-- Toast popup + bunyi: balasan chat staff & perubahan status motor --}}
<x-toast-stack
    :fetch-url="route('customer.notifications')"
    :channel="'support.'.auth()->id()"
    eventName=".message.sent"
    mode="chat"
    :chat-url="route('chat')"
    :status-channel="$vehicle ? 'vehicle.'.$vehicle->id : null"
/>

</body>
</html>
