{{-- <x-admin.icon name="mail" :size="20" />   Line icons (24x24 grid). Add a new icon = add one line to $paths. --}}
@props(['name', 'size' => 20])
@php
$paths = [
    'dashboard' => '<rect x="3" y="3" width="7" height="9" rx="1.5"/><rect x="14" y="3" width="7" height="5" rx="1.5"/><rect x="14" y="12" width="7" height="9" rx="1.5"/><rect x="3" y="16" width="7" height="5" rx="1.5"/>',
    'mail'      => '<rect x="3" y="5" width="18" height="14" rx="2.5"/><path d="m3.5 7.5 8.5 6 8.5-6"/>',
    'quote'     => '<path d="M6 3h9l4 4v14H6z"/><path d="M14 3v5h5M9 13h7M9 17h5"/>',
    'layout'    => '<rect x="3" y="4" width="18" height="16" rx="2"/><path d="M3 9h18M9 9v11"/>',
    'layers'    => '<path d="m12 3 9 5-9 5-9-5z"/><path d="m3 13 9 5 9-5"/>',
    'image'     => '<rect x="3" y="4" width="18" height="16" rx="2"/><circle cx="9" cy="10" r="1.7"/><path d="m21 16-5-5-8 8"/>',
    'box'       => '<path d="m12 3 8 4.5v9L12 21l-8-4.5v-9z"/><path d="m4 7.5 8 4.5 8-4.5M12 12v9"/>',
    'users'     => '<circle cx="9" cy="8" r="3.2"/><path d="M3 20a6 6 0 0 1 12 0"/><path d="M16 5a3 3 0 0 1 0 6M18 20a5 5 0 0 0-3-4.6"/>',
    'award'     => '<circle cx="12" cy="9" r="5.5"/><path d="m8.5 13.5-1.5 7 5-2.7 5 2.7-1.5-7"/>',
    'calendar'  => '<rect x="3" y="5" width="18" height="16" rx="2"/><path d="M3 10h18M8 3v4M16 3v4"/>',
    'handshake' => '<path d="m3 11 4-4 4 2 3-2 7 6-6 6-3-1-3 1-6-5z"/>',
    'briefcase' => '<rect x="3" y="7" width="18" height="13" rx="2"/><path d="M9 7V5a1.5 1.5 0 0 1 1.5-1.5h3A1.5 1.5 0 0 1 15 5v2M3 13h18"/>',
    'menu'      => '<path d="M4 7h16M4 12h16M4 17h16"/>',
    'logout'    => '<path d="M9 4H5a1 1 0 0 0-1 1v14a1 1 0 0 0 1 1h4M16 8l4 4-4 4M20 12H9"/>',
    'search'    => '<circle cx="11" cy="11" r="6.5"/><path d="m16 16 4.5 4.5"/>',
    'eye'       => '<path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7S2 12 2 12z"/><circle cx="12" cy="12" r="3"/>',
    'trash'     => '<path d="M4 7h16M10 11v6M14 11v6M6 7l1 13h10l1-13M9 7V4h6v3"/>',
    'send'      => '<path d="M21 3 3 10.5l6.5 2.5L12 19.5zM21 3l-11.5 10"/>',
    'download'  => '<path d="M12 4v11M7 11l5 5 5-5M4 20h16"/>',
    'paperclip' => '<path d="m20 11-8.5 8.5a5 5 0 0 1-7-7L13 4a3.3 3.3 0 0 1 4.7 4.7L9.2 17.2a1.7 1.7 0 0 1-2.4-2.4L14.5 7"/>',
    'check'     => '<path d="m5 12.5 4.5 4.5L19 7.5"/>',
    'inbox'     => '<path d="M3 13h5l1.5 3h5L16 13h5"/><path d="m5 5-2 8v6h18v-6l-2-8z"/>',
    'arrow-left'=> '<path d="M19 12H5M11 6l-6 6 6 6"/>',
];
@endphp
<svg width="{{ $size }}" height="{{ $size }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" {{ $attributes }}>{!! $paths[$name] ?? $paths['box'] !!}</svg>
