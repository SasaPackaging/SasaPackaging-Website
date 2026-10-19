{{-- Status tabs + search box above an inbox table.
     Needs: $inbox (title, route, statuses)  $counts  $status  $search --}}
<div class="adm-toolbar">
  <div class="adm-tabs" role="tablist">
    @php($tabs = ['' => ['label' => 'All']] + $inbox['statuses'])
    @foreach($tabs as $key => $meta)
      <a class="adm-tab {{ (string) $status === (string) $key ? 'is-active' : '' }}"
         href="{{ route($inbox['route'] . '.index', array_filter(['status' => $key, 'q' => $search])) }}">
        {{ $meta['label'] }}
        <span class="adm-tab__n">{{ $counts[$key === '' ? 'all' : $key] }}</span>
      </a>
    @endforeach
  </div>

  <form class="adm-search" method="get" action="{{ route($inbox['route'] . '.index') }}">
    @if($status)<input type="hidden" name="status" value="{{ $status }}">@endif
    <input class="adm-input" type="search" name="q" value="{{ $search }}" placeholder="Search…" aria-label="Search">
    <x-admin.button variant="ghost" icon="search" aria-label="Search" />
  </form>
</div>
