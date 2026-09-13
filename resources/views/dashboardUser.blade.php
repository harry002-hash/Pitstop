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
</head>
<body class="bg-white font-sans text-gray-900 antialiased">

<div class="flex min-h-screen flex-col">
  <div class="flex flex-1 flex-col md:flex-row">

    <!-- ============ SIDEBAR ============ -->
    <aside class="flex w-full shrink-0 flex-col items-start gap-8 bg-brand px-6 py-6 md:w-64 md:items-stretch md:justify-between md:gap-0 md:px-9 md:py-10">

      <!-- Logo -->
      <div class="w-32 rounded-2xl bg-white p-4 shadow-sm md:w-full">
        <!-- REPLACE: logo — swap this src for your own exported logo file -->
        <img
          src="images/logo.png"
          alt="Logo PitStop"
          class="h-auto w-full object-contain"
        >
      </div>

      <!-- nav space intentionally left empty to match the source screenshot -->
      <div class="hidden flex-1 md:block"></div>

      <!-- Social icons + logout -->
      <div class="flex w-full flex-col gap-6">
        <div class="flex items-center gap-3">
          <!-- REPLACE: social icon — Instagram -->
          <a href="#" class="block h-10 w-10 overflow-hidden rounded-full ring-2 ring-white/30 transition hover:ring-white focus-visible:outline-none focus-visible:ring-white">
            <img src="images/instagram.png" alt="Instagram" class="h-full w-full object-cover">
          </a>
          <!-- REPLACE: social icon — Facebook -->
          <a href="#" class="block h-10 w-10 overflow-hidden rounded-full ring-2 ring-white/30 transition hover:ring-white focus-visible:outline-none focus-visible:ring-white">
            <img src="images/fb.png" alt="Facebook" class="h-full w-full object-cover">
          </a>
          <!-- REPLACE: social icon — YouTube -->
          <a href="#" class="block h-10 w-10 overflow-hidden rounded-full ring-2 ring-white/30 transition hover:ring-white focus-visible:outline-none focus-visible:ring-white">
            <img src="images/yt.png" alt="YouTube" class="h-full w-full object-cover">
          </a>
        </div>

        <button type="button" class="flex w-fit items-center justify-center gap-2 rounded-full bg-white px-5 py-2.5 text-sm font-bold text-brand shadow-sm transition hover:bg-gray-50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white md:px-6 md:py-3 md:text-base">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 md:h-5 md:w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
            <polyline points="16 17 21 12 16 7"></polyline>
            <line x1="21" y1="12" x2="9" y2="12"></line>
          </svg>
          Keluar
        </button>
      </div>
    </aside>

    <!-- ============ MAIN CONTENT ============ -->
    <main class="flex-1 px-6 py-10 sm:px-10 md:px-16 md:py-14">
      <h1 class="text-4xl font-extrabold tracking-tight text-gray-900 sm:text-5xl">KB 8123 XG</h1>
      <p class="mt-2 text-base font-semibold text-gray-700 sm:text-lg">Budi Hermanto, Vario 125 Gen 1, Motor</p>

      <!-- Card -->
      <div class="mt-10 flex w-full overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-lg shadow-gray-200/60">

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
              <div class="w-full rounded-lg bg-gray-100 px-4 py-3 text-gray-600">KB 8123 XG</div>
            </div>

            <div>
              <p class="mb-2 font-bold text-gray-900">Nama Pemilik</p>
              <div class="w-full rounded-lg bg-gray-100 px-4 py-3 text-gray-600">Budi Heremanto</div>
            </div>

            <div>
              <p class="mb-2 font-bold text-gray-900">Jenis Kendaraan</p>
              <div class="w-full rounded-lg bg-gray-100 px-4 py-3 text-gray-600">Motor</div>
            </div>

            <div>
              <p class="mb-2 font-bold text-gray-900">Nama Kendaraan</p>
              <div class="w-full rounded-lg bg-gray-100 px-4 py-3 text-gray-600">Vario 125 Gen 1</div>
            </div>

            <div>
              <p class="mb-2 font-bold text-gray-900">Status</p>
              <div class="w-full rounded-lg bg-gray-100 px-4 py-3 text-gray-600">Dikerjakan</div>
            </div>

          </div>

          <div class="mt-10 flex flex-col gap-3 sm:flex-row sm:justify-end">
            <button type="button" class="rounded-lg bg-brand-gold px-6 py-3 font-bold text-white shadow-sm transition hover:brightness-95 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-gold focus-visible:ring-offset-2">
              Ubah Data Kendaraan
            </button>
            <button type="button" class="rounded-lg bg-blue-600 px-6 py-3 font-bold text-white shadow-sm transition hover:bg-blue-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-600 focus-visible:ring-offset-2">
              Chat Bengkel
            </button>
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

</body>
</html>
