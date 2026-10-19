{{-- Admin shell for every logged-in page.
     Use:  @extends('admin.layouts.app')  @section('title','...')  @section('content') ... @stop
     Page-only CSS/JS:  @push('styles') ... @endpush   @push('scripts') ... @endpush --}}
<!DOCTYPE html>
<html lang="en">
<head>
  @include('admin.partials.head')
  @stack('styles')
</head>
<body class="adm">
<div class="adm-shell" data-adm-shell>

  @include('admin.partials.sidebar')
  <div class="adm-overlay" data-adm-overlay></div>

  <div class="adm-main">
    @include('admin.partials.topbar')

    <main class="adm-content" id="main">
      @include('admin.partials.flash')
      @yield('content')
    </main>
  </div>

</div>

<script src="@adminAsset('js/admin.js')"></script>
@stack('scripts')
</body>
</html>
