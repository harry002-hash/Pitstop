<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Detail Kendaraan - Portal Servis PitStop</title>
<script src="https://cdn.tailwindcss.com"></script>
<script>
  tailwind.config = {
    theme: {
      extend: {
        colors: {
          pitred: {
            DEFAULT: '#E4241B',
            dark: '#7A0E14',
            darker: '#4A0C10',
          },
          gold: '#E7D127',
          chatblue: '#2761E7',
        },
        fontFamily: {
          sans: ['Arial', 'Helvetica', 'sans-serif'],
        }
      }
    }
  }
</script>
</head>
<body class="min-h-screen bg-white flex flex-col md:flex-row">

  <!-- Sidebar -->
  <aside class="md:w-[260px] w-full bg-pitred flex md:flex-col flex-row items-center md:items-stretch justify-between md:justify-start px-6 md:px-0 py-4 md:py-8">

    <!-- Logo: now a single image so it can be swapped without touching any code -->
    <div class="flex md:flex-col items-center md:items-start md:ml-6">
      <!-- Replace src with your own logo file, e.g. src="assets/logo-pitstop.png" -->
      <img
        src="images/logo-pitstop.png"
        alt="Logo PitStop"
        class="h-[64px] md:h-[70px] w-auto object-contain"
      >
    </div>

    <!-- Social icons (desktop: pushed toward bottom) -->
    <div class="hidden md:flex flex-col mt-auto mb-6 ml-6 gap-3">
      <div class="flex gap-3">
        <!-- Replace each src below with your own icon file (instagram.png, facebook.png, youtube.png) -->
        <a href="#" aria-label="Instagram" class="w-9 h-9 rounded-full overflow-hidden block">
          <img src="images/instagram.png" alt="Instagram" class="w-full h-full object-cover">
        </a>
        <a href="#" aria-label="Facebook" class="w-9 h-9 rounded-full overflow-hidden block">
          <img src="images/fb.png" alt="Facebook" class="w-full h-full object-cover">
        </a>
        <a href="#" aria-label="YouTube" class="w-9 h-9 rounded-full overflow-hidden block">
          <img src="images/yt.png" alt="YouTube" class="w-full h-full object-cover">
        </a>
      </div>
    </div>

    <!-- Keluar button -->
    <button type="button" class="bg-white text-pitred font-semibold text-sm px-5 py-2.5 rounded-full flex items-center gap-2 md:mx-6 md:mb-2 shadow-sm">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 3h4a2 2 0 012 2v14a2 2 0 01-2 2h-4"/><path d="M10 17l5-5-5-5"/><path d="M15 12H3"/></svg>
      Keluar
    </button>
  </aside>

  <!-- Main content -->
  <main class="flex-1 flex flex-col">
    <div class="flex-1 px-5 sm:px-10 md:px-16 py-10 md:py-14">
      <div class="max-w-4xl">

        <h1 class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-gray-900 tracking-tight">KB 8123 XG</h1>
        <p class="text-gray-800 text-sm sm:text-base md:text-lg mt-1 mb-8 md:mb-10">Budi Hermanto, Vario 125 Gen 1, Motor</p>

        <!-- Card -->
        <div class="relative bg-white rounded-3xl shadow-lg border border-gray-100 overflow-hidden">
          <!-- vertical accent stripe -->
          <div class="absolute inset-y-0 left-0 w-[60px] bg-gradient-to-b from-pitred via-pitred-dark to-pitred-darker"></div>

          <div class="pl-20 sm:pl-24 pr-6 sm:pr-10 md:pr-14 py-8 md:py-10">

            <div class="mb-6">
              <h2 class="font-bold text-gray-900 text-lg sm:text-xl mb-2">Plat Kendaraan</h2>
              <div class="bg-gray-100 rounded-lg px-4 py-3 text-gray-500 text-sm sm:text-base">KB 8123 XG</div>
            </div>

            <div class="mb-6">
              <h2 class="font-bold text-gray-900 text-lg sm:text-xl mb-2">Nama Pemilik</h2>
              <div class="bg-gray-100 rounded-lg px-4 py-3 text-gray-500 text-sm sm:text-base">Budi Heremanto</div>
            </div>

            <div class="mb-6">
              <h2 class="font-bold text-gray-900 text-lg sm:text-xl mb-2">Jenis Kendaraan</h2>
              <div class="bg-gray-100 rounded-lg px-4 py-3 text-gray-500 text-sm sm:text-base">Motor</div>
            </div>

            <div class="mb-6">
              <h2 class="font-bold text-gray-900 text-lg sm:text-xl mb-2">Nama Kendaraan</h2>
              <div class="bg-gray-100 rounded-lg px-4 py-3 text-gray-500 text-sm sm:text-base">Vario 125 Gen 1</div>
            </div>

            <div class="mb-2">
              <h2 class="font-bold text-gray-900 text-lg sm:text-xl mb-2">Status</h2>
              <div class="bg-gray-100 rounded-lg px-4 py-3 text-gray-500 text-sm sm:text-base">Dikerjakan</div>
            </div>

            <div class="flex flex-col sm:flex-row justify-end gap-3 sm:gap-4 mt-6">
              <button type="button" class="bg-gold hover:brightness-105 transition text-white font-bold px-7 py-3.5 rounded-xl text-sm sm:text-base">
                Ubah Data Kendaraan
              </button>
              <button type="button" class="bg-chatblue hover:brightness-105 transition text-white font-bold px-7 py-3.5 rounded-xl text-sm sm:text-base">
                Chat Bengkel
              </button>
            </div>

          </div>
        </div>
      </div>
    </div>

    <!-- Footer -->
    <footer class="bg-pitred-dark text-white text-xs sm:text-sm">
      <div class="max-w-6xl mx-auto px-6 py-4 flex flex-col sm:flex-row items-center justify-between gap-2">
        <span class="font-semibold">&copy; PitStop. All Rights Reserved.</span>
        <span class="text-gray-200">V1.0 | Bantuan | Kebijakan Privasi</span>
      </div>
    </footer>
  </main>

</body>
</html>
