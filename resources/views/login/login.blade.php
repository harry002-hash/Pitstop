<x-guest-layout>
    

    <div class="min-h-screen w-full flex flex-col md:flex-row overflow-hidden">

            
            <div class="relative w-full md:w-1/2 bg-black overflow-hidden flex flex-col justify-end p-8 md:p-16 min-h-[320px] md:min-h-screen">

                
                <div class="absolute inset-y-0 left-0 z-0 flex h-full">
                    <div class="h-full w-10" style="background-color:#FF0000"></div>
                    <div class="h-full w-10" style="background-color:#B30B0B"></div>
                    <div class="h-full w-10" style="background-color:#880B0B"></div>
                </div>

                //gambar
                <img
                    src="{{ asset('images/motor-hero.png') }}"
                    alt="Kendaraan"
                    class="absolute inset-0 z-10 w-full h-full object-contain object-center scale-110 pointer-events-none select-none"
                    onerror="this.style.display='none'"
                >

                
                <div class="absolute inset-0 z-20 bg-gradient-to-t from-black via-black/40 to-transparent"></div>

                
                <div class="relative z-30 text-white">
                    <h2 class="text-2xl md:text-3xl font-extrabold leading-tight">
                        Kendaraan Terawat
                        <br>
                        Perjalanan <span class="text-red-500">Lebih Hebat.</span>
                    </h2>
                    <p class="mt-3 text-sm text-gray-300 max-w-xs">
                        Layanan servis kendaraan profesional dengan teknisi berpengalaman dan peralatan modern.
                    </p>
                </div>
            </div>

            
            <div class="w-full md:w-1/2 bg-white flex items-center justify-center p-8 md:p-16 lg:p-24">
                <div class="w-full max-w-sm">

                    
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900">
                        Selamat datang <span class="text-red-600">kembali!</span>
                    </h1>
                    <p class="mt-2 text-sm text-gray-500">
                        Login untuk melanjutkan ke akun anda
                    </p>

                    
                    @if (session('status'))
                        <div class="mt-4 text-sm font-medium text-green-600">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-4">
                        @csrf

                        
                        <div>
                            <label for="username" class="sr-only">Nama Pengguna</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                        <circle cx="12" cy="7" r="4" />
                                    </svg>
                                </span>
                                <input
                                    id="username"
                                    type="text"
                                    name="username"
                                    value="{{ old('username') }}"
                                    required
                                    autofocus
                                    autocomplete="username"
                                    placeholder="Masukkan Nama Pengguna; Contoh (Budi Herlambang)"
                                    class="w-full rounded-xl border border-gray-200 bg-gray-50 py-3 pl-11 pr-4 text-sm text-gray-800 placeholder:text-gray-400 shadow-sm focus:border-red-500 focus:ring-2 focus:ring-red-500/30 focus:outline-none transition"
                                >
                            </div>
                            @error('username')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        
                        <div>
                            <label for="password" class="sr-only">Kata Sandi</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="3" y="11" width="18" height="10" rx="2" />
                                        <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                                    </svg>
                                </span>
                                <input
                                    id="password"
                                    type="password"
                                    name="password"
                                    required
                                    autocomplete="current-password"
                                    placeholder="Kata Sandi"
                                    class="w-full rounded-xl border border-gray-200 bg-gray-50 py-3 pl-11 pr-4 text-sm text-gray-800 placeholder:text-gray-400 shadow-sm focus:border-red-500 focus:ring-2 focus:ring-red-500/30 focus:outline-none transition"
                                >
                            </div>
                            @error('password')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        
                        <div class="flex items-center justify-between text-sm pt-1">
                            <label class="flex items-center gap-2 text-gray-500 cursor-pointer">
                                <input type="checkbox" name="remember" class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                                Ingat saya
                            </label>

                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="font-medium text-red-600 hover:text-red-700 hover:underline">
                                    Lupa kata sandi?
                                </a>
                            @endif
                        </div>

                        
                        <button
                            type="submit"
                            class="w-full rounded-xl bg-red-600 hover:bg-red-700 active:bg-red-800 text-white font-semibold py-3 shadow-lg shadow-red-600/30 transition"
                        >
                            Masuk
                        </button>

                        
                        <p class="text-center text-sm text-gray-500 pt-2">
                            Belum punya akun?
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="font-semibold text-red-600 hover:text-red-700 hover:underline">
                                    Daftar sekarang
                                </a>
                            @else
                                <span class="font-semibold text-red-600">Daftar sekarang</span>
                            @endif
                        </p>
                    </form>
                </div>
            </div>

    </div>
</x-guest-layout>