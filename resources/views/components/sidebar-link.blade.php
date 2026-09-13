{{-- Link navigasi sidebar. Param "active" boolean nandain halaman aktif. --}}
@props(['active' => false])
<a {{ $attributes->merge(['class' => 'rounded-lg px-4 py-2.5 text-center text-sm font-bold transition focus:outline-none focus-visible:ring-2 focus-visible:ring-white '.($active ? 'bg-red-600 text-white shadow-sm' : 'bg-white text-red-800 hover:bg-red-50')]) }}>
    {{ $slot }}
</a>
