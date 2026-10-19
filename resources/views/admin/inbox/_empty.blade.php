{{-- Shown when a list has no rows (either nothing arrived yet, or the filter matched nothing) --}}
@if($search !== '' || $status)
  <x-admin.empty-state icon="search" title="Nothing matches" text="Try another search or status.">
    <x-admin.button :href="route($inbox['route'] . '.index')" variant="ghost" size="sm">Clear filters</x-admin.button>
  </x-admin.empty-state>
@else
  <x-admin.empty-state icon="inbox" title="Nothing here yet" text="New submissions from the website will appear here." />
@endif
