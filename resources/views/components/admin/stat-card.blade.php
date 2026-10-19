{{-- <x-admin.stat-card label="New messages" :value="3" icon="mail" tone="info" :href="route(...)" /> --}}
@props(['label', 'value', 'icon' => 'box', 'tone' => null, 'href' => null])
@php($tag = $href ? 'a' : 'div')
<{{ $tag }} @if($href) href="{{ $href }}" @endif class="adm-stat {{ $tone ? 'adm-stat--' . $tone : '' }}">
  <span class="adm-stat__icon"><x-admin.icon :name="$icon" :size="22" /></span>
  <span>
    <span class="adm-stat__value">{{ $value }}</span>
    <span class="adm-stat__label">{{ $label }}</span>
  </span>
</{{ $tag }}>
