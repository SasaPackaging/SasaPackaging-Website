{{-- <x-admin.badge tone="success">Published</x-admin.badge>   tone: info | success | warning | danger | neutral --}}
@props(['tone' => 'neutral'])
<span {{ $attributes->merge(['class' => 'adm-badge adm-badge--' . $tone]) }}>{{ $slot }}</span>
