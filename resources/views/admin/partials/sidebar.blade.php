{{-- Left menu. Items come from config/admin.php > navigation (see SidebarComposer). --}}
<aside class="adm-sidebar" aria-label="Main menu">
  <a class="adm-brand" href="{{ route('admin.dashboard') }}">
    <span class="adm-brand__mark">{{ strtoupper(mb_substr(config('admin.brand') ?: config('site.name'), 0, 1)) }}</span>
    <span>{{ config('admin.brand') ?: config('site.name') }}<small>Admin panel</small></span>
  </a>

  <nav class="adm-nav">
    @foreach($adminMenu as $group)
      <div class="adm-nav__heading">{{ $group['heading'] }}</div>
      @foreach($group['items'] as $item)
        <a class="adm-nav__link {{ request()->routeIs($item['active'] ?? $item['route']) ? 'is-active' : '' }}" href="{{ route($item['route']) }}">
          <x-admin.icon :name="$item['icon']" />
          <span>{{ $item['label'] }}</span>
          @if(!empty($item['count']))<span class="adm-nav__count">{{ $item['count'] > 99 ? '99+' : $item['count'] }}</span>@endif
        </a>
      @endforeach
    @endforeach
  </nav>

  <div class="adm-sidebar__foot">
    <a href="{{ url('/') }}" target="_blank" rel="noopener" style="color:#cfdccc">View website ↗</a>
  </div>
</aside>
