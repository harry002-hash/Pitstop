{{-- Sidebar aplikasi. Slot "nav" diisi link navigasi per halaman,
     contoh: <x-slot:nav><x-sidebar-link ...>...</x-sidebar-link></x-slot:nav> --}}
<aside class="flex w-full shrink-0 flex-col gap-5 bg-red-900 px-5 py-5 md:w-60 md:px-6 md:py-8">
    <a href="{{ route('dashboard') }}" class="block w-28 shrink-0 rounded-xl p-2.5 shadow-sm md:w-full md:p-3">
        <img src="{{ asset('images/logo.png') }}" alt="PitStop" class="h-auto w-full object-contain">
    </a>

    @isset($nav)
        <nav class="flex flex-row flex-wrap gap-2 md:flex-col md:flex-wrap">
            {{ $nav }}
        </nav>
    @endisset

    <div class="mt-auto flex flex-row items-center justify-between gap-3 md:flex-col md:items-stretch">
        <div class="flex items-center gap-2.5">
            <a href="#" aria-label="Instagram" class="block h-9 w-9 overflow-hidden rounded-full ring-2 ring-white/30 transition hover:ring-white">
                <img src="{{ asset('images/instagram.png') }}" alt="Instagram" class="h-full w-full object-cover">
            </a>
            <a href="#" aria-label="Facebook" class="block h-9 w-9 overflow-hidden rounded-full ring-2 ring-white/30 transition hover:ring-white">
                <img src="{{ asset('images/fb.png') }}" alt="Facebook" class="h-full w-full object-cover">
            </a>
            <a href="#" aria-label="YouTube" class="block h-9 w-9 overflow-hidden rounded-full ring-2 ring-white/30 transition hover:ring-white">
                <img src="{{ asset('images/yt.png') }}" alt="YouTube" class="h-full w-full object-cover">
            </a>
        </div>

        <form method="POST" action="{{ route('logout') }}" class="md:w-full">
            @csrf
            <button type="submit" class="flex items-center justify-center gap-2 rounded-full bg-white px-5 py-2 text-sm font-bold text-red-800 shadow-sm transition hover:bg-red-50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white md:w-full md:py-2.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                    <polyline points="16 17 21 12 16 7"></polyline>
                    <line x1="21" y1="12" x2="9" y2="12"></line>
                </svg>
                Keluar
            </button>
        </form>
    </div>
</aside>
