{{-- Usage: <x-site.icon name="arrow" class="..." /> --}}
@props(['name'])
@php
    // [mode (s = stroke, f = fill), stroke width, viewBox, svg paths]
    $icons = [
        'arrow'    => ['s', 2,   '0 0 24 24', '<path d="M5 12h14M13 6l6 6-6 6"/>'],
        'prev'     => ['s', 2,   '0 0 24 24', '<path d="m15 5-7 7 7 7"/>'],
        'next'     => ['s', 2,   '0 0 24 24', '<path d="m9 5 7 7-7 7"/>'],
        'sliders'  => ['s', 1.6, '0 0 24 24', '<path d="M4 7h9M17 7h3M4 17h3M11 17h9"/><circle cx="15" cy="7" r="2"/><circle cx="9" cy="17" r="2"/>'],
        'cube'     => ['s', 1.6, '0 0 24 24', '<path d="M12 3 3.5 7.5v9L12 21l8.5-4.5v-9L12 3z"/><path d="M3.5 7.5 12 12l8.5-4.5M12 12v9"/>'],
        'badge'    => ['s', 1.6, '0 0 24 24', '<circle cx="12" cy="9" r="5.5"/><path d="m8.8 13.8-1.3 6.2L12 18l4.5 2-1.3-6.2"/>'],
        'calendar' => ['s', 1.6, '0 0 24 24', '<rect x="3.5" y="5" width="17" height="15.5" rx="2.5"/><path d="M3.5 10h17M8 3v4M16 3v4"/>'],
        'check'    => ['s', 1.8, '0 0 24 24', '<path d="m5 12.5 4.5 4.5L19 7.5"/>'],
        'image'    => ['s', 1.6, '0 0 24 24', '<rect x="3.5" y="5" width="17" height="14" rx="2"/><circle cx="9" cy="10.5" r="1.6"/><path d="m4 17 5-4.5 4 3.5 3-2.5 4 3.5"/>'],
        'quote'    => ['f', 0,   '0 0 24 24', '<path d="M9 7H5.5A1.5 1.5 0 0 0 4 8.5V13a1.5 1.5 0 0 0 1.5 1.5H8c0 2-1 3-3 3.5v1.5c3.5-.5 5-3 5-6V8a1 1 0 0 0-1-1zm10 0h-3.5A1.5 1.5 0 0 0 14 8.5V13a1.5 1.5 0 0 0 1.5 1.5H18c0 2-1 3-3 3.5v1.5c3.5-.5 5-3 5-6V8a1 1 0 0 0-1-1z"/>'],
        'pause'    => ['f', 0,   '0 0 12 12', '<rect x="2" y="1.5" width="3" height="9" rx="1"/><rect x="7" y="1.5" width="3" height="9" rx="1"/>'],
        'play'     => ['f', 0,   '0 0 12 12', '<path d="M3 1.8v8.4a.5.5 0 0 0 .77.42l6.5-4.2a.5.5 0 0 0 0-.84l-6.5-4.2A.5.5 0 0 0 3 1.8z"/>'],
    ];
    $icon = $icons[$name] ?? null;
@endphp
@if ($icon)
<svg {{ $attributes }} viewBox="{{ $icon[2] }}" @if ($icon[0] === 's') fill="none" stroke="currentColor" stroke-width="{{ $icon[1] }}" stroke-linecap="round" stroke-linejoin="round" @else fill="currentColor" @endif aria-hidden="true">{!! $icon[3] !!}</svg>
@endif
