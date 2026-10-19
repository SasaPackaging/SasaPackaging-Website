{{-- <x-admin.page-header title="Services" subtitle="..."> <x-slot name="actions">...</x-slot> </x-admin.page-header>
     Optional :crumbs="[['Contact messages', route(...)], ['Message #4']]" --}}
@props(['title', 'subtitle' => null, 'crumbs' => []])
<div class="adm-pagehead">
  <div>
    @if($crumbs)
    <nav class="adm-crumbs" aria-label="Breadcrumb">
      @foreach($crumbs as $crumb)
        @if(!empty($crumb[1]))<a href="{{ $crumb[1] }}">{{ $crumb[0] }}</a>@else<span>{{ $crumb[0] }}</span>@endif
      @endforeach
    </nav>
    @endif
    <h1>{{ $title }}</h1>
    @if($subtitle)<p>{{ $subtitle }}</p>@endif
  </div>
  @isset($actions)<div class="adm-pagehead__actions">{{ $actions }}</div>@endisset
</div>
