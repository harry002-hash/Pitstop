<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $title ?? 'Portal Servis PitStop' }}</title>
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
          }
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

    <!-- Logo -->
    <div class="flex md:flex-col items-center md:items-start">
      <div class="flex flex-col items-start">
        <svg width="70" height="70" viewBox="0 0 100 100" class="mb-1 md:ml-6" fill="none">
          <path d="M35 10 C30 20 25 28 28 40 C22 45 18 55 20 65 L22 90 L35 90 L34 70 C40 72 48 72 55 68 L62 90 L75 90 L68 55 C72 45 70 32 60 22 C52 14 42 12 35 10 Z" fill="white"/>
          <circle cx="45" cy="30" r="2.2" fill="#7A0E14"/>
        </svg>
        <div class="bg-white text-pitred font-extrabold text-xs md:text-sm px-3 py-1 md:ml-6 tracking-tight">
          PITSTOP
        </div>
      </div>
    </div>

    <!-- Social icons (desktop: pushed toward bottom) -->
    <div class="hidden md:flex flex-col mt-auto mb-6 ml-6 gap-3">
      <div class="flex gap-3">
        <a href="#" aria-label="Instagram" class="w-9 h-9 rounded-full bg-gradient-to-tr from-yellow-400 via-pink-500 to-purple-600 flex items-center justify-center text-white">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.2c3.2 0 3.6 0 4.8.07 1.2.06 2 .24 2.7.5.7.28 1.3.66 1.9 1.26.6.6.98 1.2 1.26 1.9.26.7.44 1.5.5 2.7.07 1.2.07 1.6.07 4.8s0 3.6-.07 4.8c-.06 1.2-.24 2-.5 2.7-.28.7-.66 1.3-1.26 1.9-.6.6-1.2.98-1.9 1.26-.7.26-1.5.44-2.7.5-1.2.07-1.6.07-4.8.07s-3.6 0-4.8-.07c-1.2-.06-2-.24-2.7-.5-.7-.28-1.3-.66-1.9-1.26-.6-.6-.98-1.2-1.26-1.9-.26-.7-.44-1.5-.5-2.7C2.2 15.6 2.2 15.2 2.2 12s0-3.6.07-4.8c.06-1.2.24-2 .5-2.7.28-.7.66-1.3 1.26-1.9.6-.6 1.2-.98 1.9-1.26.7-.26 1.5-.44 2.7-.5C8.4 2.2 8.8 2.2 12 2.2zm0 1.8c-3.15 0-3.52 0-4.76.07-1 .05-1.55.21-1.9.35-.48.19-.82.41-1.18.77-.36.36-.58.7-.77 1.18-.14.35-.3.9-.35 1.9C3 9.28 3 9.65 3 12s0 2.72.07 3.96c.05 1 .21 1.55.35 1.9.19.48.41.82.77 1.18.36.36.7.58 1.18.77.35.14.9.3 1.9.35 1.24.07 1.61.07 4.76.07s3.52 0 4.76-.07c1-.05 1.55-.21 1.9-.35.48-.19.82-.41 1.18-.77.36-.36.58-.7.77-1.18.14-.35.3-.9.35-1.9.07-1.24.07-1.61.07-3.96s0-2.72-.07-3.96c-.05-1-.21-1.55-.35-1.9a3.2 3.2 0 00-.77-1.18 3.2 3.2 0 00-1.18-.77c-.35-.14-.9-.3-1.9-.35C15.52 4 15.15 4 12 4zm0 3.5a4.5 4.5 0 110 9 4.5 4.5 0 010-9zm0 1.8a2.7 2.7 0 100 5.4 2.7 2.7 0 000-5.4zm5.7-2a1.05 1.05 0 11-2.1 0 1.05 1.05 0 012.1 0z"/></svg>
        </a>
        <a href="#" aria-label="Facebook" class="w-9 h-9 rounded-full bg-[#1877F2] flex items-center justify-center text-white">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M13.5 22v-8.4h2.8l.4-3.3h-3.2V8.1c0-.95.27-1.6 1.63-1.6h1.74V3.5c-.3-.04-1.33-.13-2.53-.13-2.5 0-4.2 1.53-4.2 4.33v2.42H7.1v3.3h2.86V22h3.54z"/></svg>
        </a>
        <a href="#" aria-label="YouTube" class="w-9 h-9 rounded-full bg-[#FF0000] flex items-center justify-center text-white">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M22 12s0-3.1-.4-4.6a3 3 0 00-2.1-2.1C17.9 5 12 5 12 5s-5.9 0-7.5.3a3 3 0 00-2.1 2.1C2 8.9 2 12 2 12s0 3.1.4 4.6a3 3 0 002.1 2.1C6.1 19 12 19 12 19s5.9 0 7.5-.3a3 3 0 002.1-2.1C22 15.1 22 12 22 12zM10 15V9l5 3-5 3z"/></svg>
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
    <div class="flex-1 flex flex-col items-center px-6 py-10 md:py-16">
      <h1 class="text-2xl md:text-4xl font-extrabold text-gray-900 tracking-tight mb-8 md:mb-10 text-center">
        PORTAL&nbsp;&nbsp;SERVIS&nbsp;PITSTOP
      </h1>

      <div class="relative w-full max-w-3xl">
        <!-- Card -->
        <div class="relative overflow-hidden rounded-2xl bg-black shadow-xl">
          <!-- gradient accent stripe -->
          <div class="absolute inset-y-0 left-0 w-32 bg-gradient-to-r from-pitred via-pitred-dark to-pitred-darker opacity-90"></div>

          <!-- decorative motorcycle silhouette -->
          <svg class="hidden sm:block absolute right-0 bottom-0 w-2/5 h-auto opacity-95" viewBox="0 0 400 220" fill="none">
            <g>
              <circle cx="90" cy="175" r="38" stroke="#e5e5e5" stroke-width="6"/>
              <circle cx="300" cy="175" r="38" stroke="#e5e5e5" stroke-width="6"/>
              <path d="M90 175 L160 110 L230 120 L300 175" stroke="#E4241B" stroke-width="8" fill="none" stroke-linecap="round"/>
              <path d="M160 110 L150 70 L200 60" stroke="#E4241B" stroke-width="8" fill="none" stroke-linecap="round"/>
              <path d="M230 120 L260 90 L300 100" stroke="#e5e5e5" stroke-width="6" fill="none" stroke-linecap="round"/>
              <rect x="140" y="95" width="60" height="20" rx="6" fill="#E4241B"/>
            </g>
          </svg>

          <form class="relative z-10 px-6 sm:px-10 py-10 sm:py-12 flex flex-col gap-5 max-w-md">
            <label class="flex items-center gap-3 bg-white rounded-xl px-5 py-4">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#111" stroke-width="1.8" class="shrink-0">
                <rect x="3" y="6" width="18" height="13" rx="2"/>
                <path d="M7 6V4.5A1.5 1.5 0 018.5 3h7A1.5 1.5 0 0117 4.5V6"/>
                <path d="M7 12h10M7 15h6"/>
              </svg>
              <input
                type="text"
                name="plat"
                placeholder="Masukkan Plat Kendaraan"
                class="w-full bg-transparent outline-none text-gray-800 placeholder-gray-500 text-sm sm:text-base"
              >
            </label>

            <label class="flex items-center gap-3 bg-white rounded-xl px-5 py-4">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#111" stroke-width="1.8" class="shrink-0">
                <rect x="4" y="10" width="16" height="10" rx="2"/>
                <path d="M8 10V7a4 4 0 118 0v3"/>
                <circle cx="12" cy="15" r="1.2" fill="#111"/>
              </svg>
              <input
                type="password"
                name="password"
                placeholder="Kata Sandi"
                class="w-full bg-transparent outline-none text-gray-800 placeholder-gray-500 text-sm sm:text-base"
              >
            </label>

            <button
              type="button"
              class="mt-2 bg-gradient-to-r from-pitred to-pitred-dark hover:brightness-110 transition text-white font-bold py-4 rounded-xl text-sm sm:text-base"
            >
              Masuk
            </button>
          </form>
        </div>

        <!-- Wrench decoration -->
        <svg class="hidden sm:block absolute -bottom-8 -right-4 w-16 h-16 text-pitred drop-shadow-lg rotate-[20deg]" viewBox="0 0 24 24" fill="currentColor">
          <path d="M22.7 19.3l-5.4-5.4c1-2 .6-4.5-1.2-6.2-1.9-1.9-4.6-2.3-6.8-1.2l3 3-2.1 2.1-3-3c-1.1 2.2-.7 4.9 1.2 6.8 1.8 1.8 4.3 2.2 6.2 1.2l5.4 5.4c.4.4 1 .4 1.4 0l1.3-1.3c.4-.4.4-1.1 0-1.4z"/>
        </svg>
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