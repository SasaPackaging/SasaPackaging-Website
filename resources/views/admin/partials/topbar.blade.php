<header class="adm-topbar">
  <button type="button" class="adm-iconbtn adm-burger" data-adm-menu-toggle aria-label="Open menu"><x-admin.icon name="menu" /></button>
  <span class="adm-topbar__title">@yield('title', 'Admin')</span>
  <span class="adm-topbar__spacer"></span>

  @php($admin = auth('admin')->user())
  <div class="adm-topbar__user">
    <span class="adm-avatar" aria-hidden="true">{{ mb_substr($admin->name, 0, 1) }}</span>
    <span>{{ $admin->name }}</span>
    <form method="post" action="{{ route('admin.logout') }}" class="adm-inline-form">
      @csrf
      <button type="submit" class="adm-btn adm-btn--ghost adm-btn--sm"><x-admin.icon name="logout" :size="16" /> Log out</button>
    </form>
  </div>
</header>
