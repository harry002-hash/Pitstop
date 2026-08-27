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
    <main class="flex-grow flex flex-col justify-center items-center px-4 py-8">
        <div class="w-full max-w-[750px]">
            
            <!-- Header Judul -->
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-black tracking-tight">Ubah Data Kendaraan</h1>
                <p class="text-gray-500 text-sm mt-1">Memperbarui data</p>
            </div>

            <!-- Notifikasi Sukses -->
            @if(session('success'))
                <div class="mb-4 p-3 bg-green-600 text-white rounded-lg text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Form Card Putih Utama -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-row">
                
                <!-- Strip Merah 3 Layer Kiri -->
                <div class="flex flex-row flex-shrink-0">
                    <div class="w-4 bg-[#e50914]"></div>
                    <div class="w-4 bg-[#b20710]"></div>
                    <div class="w-4 bg-[#7e0000]"></div>
                </div>

                <!-- Form Area -->
                <form action="{{ route('kendaraan.update', $kendaraan['id'] ?? 1) }}" method="POST" class="flex-1 p-8 flex flex-col space-y-4">
                    @csrf
                    @method('PUT')
                    
                    <!-- Plat Kendaraan -->
                    <div class="flex flex-col">
                        <label for="plat_kendaraan" class="text-black font-semibold text-sm mb-1.5">Plat Kendaraan</label>
                        <input 
                            type="text" 
                            id="plat_kendaraan"
                            name="plat_kendaraan"
                            value="{{ old('plat_kendaraan', $kendaraan['plat_kendaraan'] ?? 'KB 8123 XG') }}" 
                            class="w-full bg-[#f4f4f4] text-gray-700 text-xs font-medium rounded-lg px-4 py-3 border border-gray-200 focus:outline-none focus:bg-white focus:border-red-500 transition-all"
                            required
                        />
                    </div>

                    <!-- Nama Pemilik -->
                    <div class="flex flex-col">
                        <label for="nama_pemilik" class="text-black font-semibold text-sm mb-1.5">Nama Pemilik</label>
                        <input 
                            type="text" 
                            id="nama_pemilik"
                            name="nama_pemilik"
                            value="{{ old('nama_pemilik', $kendaraan['nama_pemilik'] ?? 'Budi Heremanto') }}" 
                            class="w-full bg-[#f4f4f4] text-gray-700 text-xs font-medium rounded-lg px-4 py-3 border border-gray-200 focus:outline-none focus:bg-white focus:border-red-500 transition-all"
                            required
                        />
                    </div>

                    <!-- Jenis Kendaraan -->
                    <div class="flex flex-col">
                        <label for="jenis_kendaraan" class="text-black font-semibold text-sm mb-1.5">Jenis Kendaraan</label>
                        <input 
                            type="text" 
                            id="jenis_kendaraan"
                            name="jenis_kendaraan"
                            value="{{ old('jenis_kendaraan', $kendaraan['jenis_kendaraan'] ?? 'Motor') }}" 
                            class="w-full bg-[#f4f4f4] text-gray-700 text-xs font-medium rounded-lg px-4 py-3 border border-gray-200 focus:outline-none focus:bg-white focus:border-red-500 transition-all"
                            required
                        />
                    </div>

                    <!-- Nama Kendaraan -->
                    <div class="flex flex-col">
                        <label for="nama_kendaraan" class="text-black font-semibold text-sm mb-1.5">Nama Kendaraan</label>
                        <input 
                            type="text" 
                            id="nama_kendaraan"
                            name="nama_kendaraan"
                            value="{{ old('nama_kendaraan', $kendaraan['nama_kendaraan'] ?? 'Vario 125 Gen 1') }}" 
                            class="w-full bg-[#f4f4f4] text-gray-700 text-xs font-medium rounded-lg px-4 py-3 border border-gray-200 focus:outline-none focus:bg-white focus:border-red-500 transition-all"
                            required
                        />
                    </div>

                    <!-- Status Dropdown -->
                    <div class="flex flex-col">
                        <label for="status" class="text-black font-semibold text-sm mb-1.5">Status</label>
                        <div class="relative w-full">
                            <select 
                                id="status"
                                name="status" 
                                class="w-full bg-[#f4f4f4] text-gray-700 text-xs font-medium rounded-lg px-4 py-3 border border-gray-200 appearance-none focus:outline-none focus:bg-white focus:border-red-500 pr-10 cursor-pointer"
                            >
                                <option value="Dikerjakan" {{ (old('status', $kendaraan['status'] ?? '') == 'Dikerjakan') ? 'selected' : '' }}>Dikerjakan</option>
                                <option value="Selesai" {{ (old('status', $kendaraan['status'] ?? '') == 'Selesai') ? 'selected' : '' }}>Selesai</option>
                                <option value="Pending" {{ (old('status', $kendaraan['status'] ?? '') == 'Pending') ? 'selected' : '' }}>Pending</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-600">
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                                    <path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Batal (Merah) & Perbarui (Biru) -->
                    <div class="flex flex-row justify-end space-x-3 pt-6">
                        <button 
                            type="button" 
                            onclick="window.history.back()"
                            class="bg-[#e50914] hover:bg-red-700 text-white font-bold text-xs px-8 py-2.5 rounded-lg shadow-sm transition duration-200 cursor-pointer"
                        >
                            Batal
                        </button>
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

    <!-- Footer Bar Merah Gelap -->
    <footer class="w-full bg-[#700000] text-white py-3.5 px-6 relative mt-auto flex items-center justify-between">
        <div class="w-full flex justify-center items-center space-x-2 font-medium text-xs md:text-sm">
            <span>©</span>
            <span>PitStop. All Rights Reserved.</span>
        </div>
        <div class="absolute right-6 bottom-3 hidden sm:flex space-x-1.5 text-gray-400 text-[10px]">
            <span>V1.0</span>
            <span>|</span>
            <a href="#" class="hover:underline">Bantuan</a>
            <span>|</span>
            <a href="#" class="hover:underline">Kebijakan Privasi</a>
        </div>
    </footer>

</body>
</html>