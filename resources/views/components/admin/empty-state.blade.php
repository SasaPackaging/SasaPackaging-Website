{{-- <x-admin.empty-state icon="inbox" title="Nothing here yet" text="..."> optional action button </x-admin.empty-state> --}}
@props(['icon' => 'inbox', 'title', 'text' => null])
<div class="adm-empty">
  <span class="adm-empty__icon"><x-admin.icon :name="$icon" :size="26" /></span>
  <h3>{{ $title }}</h3>
  @if($text)<p>{{ $text }}</p>@endif
  <div style="margin-top:14px">{{ $slot }}</div>
</div>
