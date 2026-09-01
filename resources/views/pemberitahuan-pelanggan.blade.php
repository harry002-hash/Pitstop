<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemberitahuan Pelanggan - PitStop</title>
    <!-- CDN Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col justify-between font-sans antialiased m-0 p-0">

    <!-- Area Konten Utama -->
    <main class="flex-grow flex flex-col justify-center items-center px-4 py-8">
        <div class="w-full max-w-[850px]">
            
            <!-- Header Judul -->
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-black tracking-tight">Pemberitahuan Pelanggan</h1>
                <p class="text-gray-500 text-sm mt-1">Pemberitahuan data <span class="font-bold text-black">{{ $kendaraan['plat_kendaraan'] ?? 'KB 8123 XG' }}</span></p>
            </div>

            <!-- Notifikasi Sukses (Opsional) -->
            @if(session('success'))
                <div class="mb-4 p-3 bg-green-600 text-white rounded-lg text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Form Card Putih Utama -->
            <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden flex flex-row">
                
                <!-- Strip Merah 3 Layer Kiri -->
                <div class="flex flex-row flex-shrink-0">
                    <div class="w-4 bg-[#e50914]"></div>
                    <div class="w-4 bg-[#b20710]"></div>
                    <div class="w-4 bg-[#7e0000]"></div>
                </div>

                <!-- Form Area -->
                <form action="{{ route('vehicles.kirimPemberitahuan', $kendaraan['id'] ?? 1) }}" method="POST" class="flex-1 p-8 flex flex-col">
                    @csrf
                    
                    <!-- Top Info: Data Kendaraan (Disabled Inputs) -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 pb-4 border-b border-gray-300">
                        <div>
                            <label class="block text-gray-400 font-semibold text-xs mb-1">Plat Kendaraan</label>
                            <input type="text" value="{{ $kendaraan['plat_kendaraan'] ?? 'KB 8123 XG' }}" disabled class="w-full bg-[#f0f0f0] text-gray-800 text-xs font-semibold rounded-lg px-3 py-2 border border-gray-200" />
                        </div>
                        <div>
                            <label class="block text-gray-400 font-semibold text-xs mb-1">Nama Pemilik</label>
                            <input type="text" value="{{ $kendaraan['nama_pemilik'] ?? 'Budi Hermanto' }}" disabled class="w-full bg-[#f0f0f0] text-gray-800 text-xs font-semibold rounded-lg px-3 py-2 border border-gray-200" />
                        </div>
                        <div>
                            <label class="block text-gray-400 font-semibold text-xs mb-1">Jenis Kendaraan</label>
                            <input type="text" value="{{ $kendaraan['jenis_kendaraan'] ?? 'Motor' }}" disabled class="w-full bg-[#f0f0f0] text-gray-800 text-xs font-semibold rounded-lg px-3 py-2 border border-gray-200" />
                        </div>
                        <div>
                            <label class="block text-gray-400 font-semibold text-xs mb-1">Nama Kendaraan</label>
                            <input type="text" value="{{ $kendaraan['nama_kendaraan'] ?? 'Vario 125 Gen 1' }}" disabled class="w-full bg-[#f0f0f0] text-gray-800 text-xs font-semibold rounded-lg px-3 py-2 border border-gray-200" />
                        </div>
                    </div>

                    <!-- Body: Opsi & Rincian Sparepart -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 py-6 border-b border-gray-200">
                        
                        <!-- Kiri: Radio Button Opsi Pemberitahuan -->
                        <div class="pr-0 md:pr-4 border-r-0 md:border-r border-gray-300">
                            <label class="block text-gray-600 font-semibold text-xs mb-3">Pilih Jenis Pemberitahuan :</label>
                            <div class="space-y-2.5 text-xs font-semibold text-gray-800">
                                <label class="flex items-center space-x-2.5 cursor-pointer">
                                    <input type="radio" name="jenis_pemberitahuan" value="Pergantian sparepart" checked class="w-4 h-4 text-black focus:ring-0 accent-black">
                                    <span>Pergantian sparepart</span>
                                </label>
                                <label class="flex items-center space-x-2.5 cursor-pointer">
                                    <input type="radio" name="jenis_pemberitahuan" value="Servis Selesai (Siap Diambil)" class="w-4 h-4 text-black focus:ring-0 accent-black">
                                    <span>Servis Selesai (Siap Diambil)</span>
                                </label>
                                <label class="flex items-center space-x-2.5 cursor-pointer">
                                    <input type="radio" name="jenis_pemberitahuan" value="Pengingat servis berkala" class="w-4 h-4 text-black focus:ring-0 accent-black">
                                    <span>Pengingat servis berkala</span>
                                </label>
                            </div>
                        </div>

                        <!-- Kanan: Rincian Sparepart & Biaya -->
                        <div>
                            <label class="block text-gray-600 font-semibold text-xs mb-2">Rincian Part & Biaya (Sudah termasuk jasa)</label>
                            
                            <ul class="text-xs space-y-1.5 font-bold text-gray-800 mb-4">
                                @forelse($spareparts ?? [] as $item)
                                    <li class="flex justify-between items-center">
                                        <span>• {{ $item['nama'] }}</span>
                                        <span>Rp {{ number_format($item['harga'], 0, ',', '.') }}</span>
                                    </li>
                                @empty
                                    <li class="flex justify-between items-center">
                                        <span>• Busi Standard</span>
                                        <span>Rp 25.000</span>
                                    </li>
                                    <li class="flex justify-between items-center">
                                        <span>• Air Radiator Honda</span>
                                        <span>Rp 35.000</span>
                                    </li>
                                    <li class="flex justify-between items-center">
                                        <span>• Oli MPX2 Synthetic</span>
                                        <span>Rp 70.000</span>
                                    </li>
                                @endforelse
                            </ul>

                            <!-- Tombol Tambah Sparepart -->
                            <button type="button" class="bg-[#22c55e] hover:bg-green-600 text-white font-bold text-[11px] px-3 py-1.5 rounded-md shadow-sm transition duration-200 flex items-center space-x-1 mb-4 cursor-pointer">
                                <span>+</span>
                                <span>Tambah Sparepart</span>
                            </button>

                            <!-- Total Biaya -->
                            <div class="text-xs font-bold text-black">
                                Total biaya : Rp {{ number_format($totalBiaya ?? 130000, 0, ',', '.') }}
                            </div>
                        </div>

                    </div>

                    <!-- Catatan Pelanggan (Textarea) -->
                    <div class="mt-6 mb-6">
                        <textarea 
                            name="catatan" 
                            rows="3" 
                            placeholder="Catatan pelanggan..."
                            class="w-full bg-[#f4f4f4] text-gray-700 text-xs font-medium rounded-xl p-4 border border-gray-200 focus:outline-none focus:bg-white focus:border-gray-400 transition-all resize-none"
                        ></textarea>
                    </div>

                    <!-- Tombol Action: Batal & Kirim -->
                    <div class="flex flex-row justify-end space-x-4">
                        <button 
                            type="button" 
                            onclick="window.history.back()"
                            class="bg-[#e50914] hover:bg-red-700 text-white font-bold text-xs px-10 py-2.5 rounded-lg shadow-md transition duration-200 cursor-pointer"
                        >
                            Batal
                        </button>
                        <button 
                            type="submit" 
                            class="bg-[#22c55e] hover:bg-green-600 text-white font-bold text-xs px-10 py-2.5 rounded-lg shadow-md transition duration-200 cursor-pointer"
                        >
                            Kirim
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