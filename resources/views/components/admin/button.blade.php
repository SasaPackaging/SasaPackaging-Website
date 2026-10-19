{{-- <x-admin.button href="..." variant="ghost" icon="eye" size="sm">Label</x-admin.button>
     Without href it renders a <button type="submit">.  variant: primary | sage | ghost | danger --}}
@props(['href' => null, 'variant' => 'primary', 'icon' => null, 'size' => null, 'type' => 'submit', 'block' => false])
@php
$class = 'adm-btn'
    . ($variant !== 'primary' ? ' adm-btn--' . $variant : '')
    . ($size ? ' adm-btn--' . $size : '')
    . ($block ? ' adm-btn--block' : '');
@endphp
@if($href)
<a href="{{ $href }}" {{ $attributes->merge(['class' => $class]) }}>@if($icon)<x-admin.icon :name="$icon" :size="16" />@endif{{ $slot }}</a>
@else
<button type="{{ $type }}" {{ $attributes->merge(['class' => $class]) }}>@if($icon)<x-admin.icon :name="$icon" :size="16" />@endif{{ $slot }}</button>
@endif
