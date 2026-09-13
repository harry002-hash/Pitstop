<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubah Data Kendaraan - PitStop</title>
    <!-- CDN Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col justify-between font-sans antialiased m-0 p-0">

    <!-- Area Konten Utama -->
    <main class="flex-grow flex flex-col justify-center items-center px-4 py-6">
        <div class="w-full max-w-xl">

            <!-- Header Judul -->
            <div class="mb-5">
                <h1 class="text-2xl font-bold text-black tracking-tight">Ubah Data Kendaraan</h1>
                <p class="text-gray-500 text-sm mt-1">Memperbarui data {{ $vehicle->plate_number }}</p>
            </div>

            <!-- Notifikasi Sukses -->
            @if(session('status'))
                <div class="mb-4 p-3 bg-green-600 text-white rounded-lg text-sm">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Form Card Putih Utama -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-row">

                <!-- Strip Merah 3 Layer Kiri -->
                <div class="flex flex-row flex-shrink-0">
                    <div class="w-3 bg-[#e50914]"></div>
                    <div class="w-3 bg-[#b20710]"></div>
                    <div class="w-3 bg-[#7e0000]"></div>
                </div>

                <!-- Form Area -->
                <form action="{{ route('customer.vehicle.update') }}" method="POST" class="flex-1 p-6 flex flex-col space-y-4">
                    @csrf
                    @method('PUT')

                    <!-- Plat Kendaraan -->
                    <div class="flex flex-col">
                        <label for="plate_number" class="text-black font-semibold text-sm mb-1.5">Plat Kendaraan</label>
                        <input
                            type="text"
                            id="plate_number"
                            name="plate_number"
                            value="{{ old('plate_number', $vehicle->plate_number) }}"
                            class="w-full bg-[#f4f4f4] text-gray-700 text-xs font-medium rounded-lg px-4 py-3 border border-gray-200 focus:outline-none focus:bg-white focus:border-red-500 transition-all"
                            required maxlength="20"
                        />
                    </div>

                    <!-- Nama Kendaraan -->
                    <div class="flex flex-col">
                        <label for="vehicle_name" class="text-black font-semibold text-sm mb-1.5">Nama Kendaraan</label>
                        <input
                            type="text"
                            id="vehicle_name"
                            name="vehicle_name"
                            value="{{ old('vehicle_name', $vehicle->vehicle_name) }}"
                            class="w-full bg-[#f4f4f4] text-gray-700 text-xs font-medium rounded-lg px-4 py-3 border border-gray-200 focus:outline-none focus:bg-white focus:border-red-500 transition-all"
                            required maxlength="100"
                        />
                    </div>

                    <!-- Nama Pemilik (read-only, ikut akun) -->
                    <div class="flex flex-col">
                        <span class="text-black font-semibold text-sm mb-1.5">Nama Pemilik</span>
                        <div class="w-full bg-[#f4f4f4] text-gray-500 text-xs font-medium rounded-lg px-4 py-3 border border-gray-200">
                            {{ auth()->user()->username }}
                        </div>
                    </div>

                    <!-- Jenis Kendaraan (read-only) -->
                    <div class="flex flex-col">
                        <span class="text-black font-semibold text-sm mb-1.5">Jenis Kendaraan</span>
                        <div class="w-full bg-[#f4f4f4] text-gray-500 text-xs font-medium rounded-lg px-4 py-3 border border-gray-200">
                            Motor
                        </div>
                    </div>

                    <!-- Status (read-only, wewenang bengkel) -->
                    <div class="flex flex-col">
                        <span class="text-black font-semibold text-sm mb-1.5">Status</span>
                        <div class="w-full bg-[#f4f4f4] text-gray-500 text-xs font-medium rounded-lg px-4 py-3 border border-gray-200">
                            {{ $vehicle->statusLabel() }}
                        </div>
                    </div>

                    <!-- Tombol Batal (Merah) & Perbarui (Biru) -->
                    <div class="flex flex-row justify-end space-x-3 pt-4">
                        <a
                            href="{{ route('customer.dashboard') }}"
                            class="bg-[#e50914] hover:bg-red-700 text-white font-bold text-xs px-8 py-2.5 rounded-lg shadow-sm transition duration-200 cursor-pointer"
                        >
                            Batal
                        </a>
                        <button
                            type="submit"
                            class="bg-[#2563eb] hover:bg-blue-700 text-white font-bold text-xs px-8 py-2.5 rounded-lg shadow-sm transition duration-200 cursor-pointer"
                        >
                            Perbarui
                        </button>
                    </div>

                </form>
            </div>

        </div>
    </main>

    <x-app-footer />

</body>
</html>
