```blade
@props([
    'type' => 'error',
    'message' => null,
])

@php
    $styles = [
        'error' => [
            'container' => 'bg-red-50 border-red-200 text-red-700',
            'icon' => 'text-red-500',
        ],
        'success' => [
            'container' => 'bg-green-50 border-green-200 text-green-700',
            'icon' => 'text-green-500',
        ],
        'warning' => [
            'container' => 'bg-yellow-50 border-yellow-200 text-yellow-700',
            'icon' => 'text-yellow-500',
        ],
        'info' => [
            'container' => 'bg-blue-50 border-blue-200 text-blue-700',
            'icon' => 'text-blue-500',
        ],
    ];

    $style = $styles[$type] ?? $styles['error'];
@endphp

@if ($message)
    <div
        {{ $attributes->merge([
            'class' => "flex items-start gap-3 rounded-xl border p-4 text-sm {$style['container']}"
        ]) }}
        role="alert"
    >
        {{-- Icon --}}
        <svg
            class="w-5 h-5 mt-0.5 shrink-0 {{ $style['icon'] }}"
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
            stroke-width="2"
        >
            @if ($type === 'success')
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M9 12.75 11.25 15 15 9.75"
                />
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"
                />
            @elseif ($type === 'warning')
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M12 9v3.75m0 3h.008v.008H12V15.75Z"
                />
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0Z"
                />
            @else
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M12 9v3.75m0 3h.008v.008H12V15.75Z"
                />
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0Z"
                />
            @endif
        </svg>

        <div class="flex-1">
            {{ $message }}
        </div>
    </div>
@endif
```
