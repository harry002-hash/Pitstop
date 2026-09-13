<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Klaim Motor - Pitstop</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white font-sans antialiased">

    <div class="min-h-screen flex flex-col">
        <div class="flex flex-col md:flex-row flex-1">

            {{-- Sidebar --}}
            <aside class="w-full md:w-64 shrink-0 bg-gradient-to-b from-red-700 to-red-950 flex flex-row md:flex-col items-center justify-between px-6 py-4 md:py-10">

                {{-- Logo slot: fixed size box, drop your logo file in public/images and it will scale to fit --}}
                <div class="w-20 h-20 md:w-28 md:h-28 flex items-center justify-center shrink-0">
                    <img src="{{ asset('images/logo.png') }}" alt="Pitstop Logo" class="max-w-full max-h-full object-contain">
                </div>

                {{-- Social icons + logout --}}
                <div class="flex flex-row md:flex-col items-center gap-3 md:gap-6 md:w-full md:px-4">
                    <div class="hidden sm:flex items-center gap-3">
                        <a href="#" aria-label="Instagram" class="w-8 h-8 rounded-full flex items-center justify-center text-white bg-gradient-to-tr from-yellow-400 via-pink-500 to-purple-600">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="3" width="18" height="18" rx="5"/>
                                <circle cx="12" cy="12" r="4"/>
                                <circle cx="17.5" cy="6.5" r=".6" fill="currentColor" stroke="none"/>
                            </svg>
                        </a>
                        <a href="#" aria-label="Facebook" class="w-8 h-8 rounded-full flex items-center justify-center text-white bg-blue-600">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M13.5 21v-8h2.7l.4-3.1h-3.1V7.9c0-.9.3-1.5 1.6-1.5h1.7V3.6C15.9 3.5 15 3.4 14 3.4c-2.3 0-3.9 1.4-3.9 4v2.5H7.4V13h2.7v8h3.4z"/>
                            </svg>
                        </a>
                        <a href="#" aria-label="YouTube" class="w-8 h-8 rounded-full flex items-center justify-center text-white bg-red-600">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M9.5 7.5v9l8-4.5-8-4.5z"/>
                            </svg>
                        </a>
                    </div>

                    <form method="POST" action="{{ route('logout') }}" class="md:w-full">
                        @csrf
                        <button type="submit" class="bg-white hover:bg-red-50 text-red-600 font-bold rounded-full px-5 py-2 md:py-2.5 md:w-full flex items-center justify-center gap-2 transition text-sm focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-red-800">
                            <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/>
                                <polyline points="16 17 21 12 16 7"/>
                                <line x1="21" y1="12" x2="9" y2="12"/>
                            </svg>
                            Keluar
                        </button>
                    </form>
                </div>
            </aside>

            {{-- Main content --}}
            <main class="flex-1 flex items-center justify-center px-4 sm:px-6 py-10">
                <div class="w-full max-w-4xl">
                    <h1 class="text-center text-2xl sm:text-3xl md:text-4xl font-extrabold text-gray-900 tracking-wide mb-6 md:mb-8">
                        PORTAL SERVIS PITSTOP
                    </h1>

                    <div class="relative">
                        <div class="relative bg-black rounded-3xl shadow-xl overflow-hidden flex min-h-[300px]">

                            {{-- Gradient stripe --}}
                            <div class="hidden sm:flex w-6 md:w-10 lg:w-16 shrink-0">
                                <div class="flex-1 bg-red-500"></div>
                                <div class="flex-1 bg-red-700"></div>
                                <div class="flex-1 bg-red-900"></div>
                                <div class="flex-1 bg-red-950"></div>
                            </div>

                            {{-- Stacked layer: motorcycle behind, form in front on top of it --}}
                            <div class="relative flex-1">

                                {{-- Motorcycle image: behind the form --}}
                                <img
                                    src="{{ asset('images/moto1.png') }}"
                                    alt="Motorcycle"
                                    class="hidden md:block absolute right-0 bottom-0 h-[90%] w-auto max-w-[65%] object-contain object-right-bottom z-0 pointer-events-none select-none"
                                >

                                {{-- Form: in front of the motorcycle --}}
                                <div class="relative z-10 h-full md:max-w-md p-6 sm:p-8 md:p-10 flex flex-col justify-center">

                                    <p class="text-gray-300 text-xs mb-1">
                                        Login sebagai: <span class="text-white font-semibold">{{ auth()->user()->username }}</span>
                                    </p>
                                    <p class="text-gray-400 text-xs mb-5 leading-relaxed">
                                        Masukkan nomor KB + password yang diberikan bengkel.
                                    </p>

                                    @if ($errors->any())
                                        <div class="bg-red-950/70 border border-red-700 text-red-200 text-xs sm:text-sm rounded-2xl px-4 py-3 mb-4">
                                            <ul class="list-disc list-inside space-y-0.5">
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    <form method="POST" action="{{ route('vehicle.claim.store') }}" class="space-y-4">
                                        @csrf

                                        <div>
                                            <label for="plate_number" class="sr-only">Nomor Plat Kendaraan</label>
                                            <div class="bg-white rounded-full flex items-center gap-3 px-5 py-3.5 focus-within:ring-2 focus-within:ring-red-400 transition">
                                                <svg class="w-5 h-5 text-gray-400 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <rect x="3" y="6" width="18" height="13" rx="2"/>
                                                    <line x1="7" y1="10" x2="13" y2="10"/>
                                                    <line x1="7" y1="14" x2="17" y2="14"/>
                                                </svg>
                                                <input
                                                    type="text"
                                                    id="plate_number"
                                                    name="plate_number"
                                                    value="{{ old('plate_number') }}"
                                                    placeholder="Masukkan Plat Kendaraan"
                                                    required
                                                    autofocus
                                                    class="flex-1 min-w-0 outline-none bg-transparent text-gray-800 placeholder-gray-500 text-sm"
                                                >
                                            </div>
                                        </div>

                                        <div>
                                            <label for="plate_password" class="sr-only">Password KB</label>
                                            <div class="bg-white rounded-full flex items-center gap-3 px-5 py-3.5 focus-within:ring-2 focus-within:ring-red-400 transition">
                                                <svg class="w-5 h-5 text-gray-400 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <rect x="4" y="10" width="16" height="10" rx="2"/>
                                                    <path d="M8 10V7a4 4 0 018 0v3"/>
                                                    <circle cx="12" cy="15" r="1.4" fill="currentColor" stroke="none"/>
                                                </svg>
                                                <input
                                                    type="password"
                                                    id="plate_password"
                                                    name="plate_password"
                                                    placeholder="Kata Sandi"
                                                    required
                                                    class="flex-1 min-w-0 outline-none bg-transparent text-gray-800 placeholder-gray-500 text-sm"
                                                >
                                            </div>
                                        </div>

                                        <button type="submit" class="w-full bg-red-600 hover:bg-red-700 active:bg-red-800 transition text-white font-bold rounded-full py-3.5 tracking-wide focus:outline-none focus:ring-2 focus:ring-red-300 focus:ring-offset-2">
                                            Masuk
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
        {{-- Footer --}}
        <footer class="bg-red-950 py-4 px-6 relative">
            <p class="text-center text-white text-xs sm:text-sm font-medium">&copy; PitStop. All Rights Reserved.</p>
            <span class="hidden md:block absolute right-6 top-1/2 -translate-y-1/2 text-red-300 text-xs">
                V1.0 &nbsp;|&nbsp; Bantuan &nbsp;|&nbsp; Kebijakan Privasi
            </span>
        </footer>
    </div>

</body>
</html>
