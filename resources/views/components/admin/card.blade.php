{{-- <x-admin.card title="Heading" :flush="true" class="..."> body <x-slot name="actions">buttons</x-slot> </x-admin.card> --}}
@props(['title' => null, 'flush' => false])
<section {{ $attributes->merge(['class' => 'adm-card']) }}>
  @if($title || isset($actions))
  <div class="adm-card__head">
    @if($title)<h2>{{ $title }}</h2>@endif
    @isset($actions)<div class="adm-row">{{ $actions }}</div>@endisset
  </div>
  @endif
  <div class="adm-card__body {{ $flush ? 'adm-card__body--flush' : '' }}">{{ $slot }}</div>
</section>
