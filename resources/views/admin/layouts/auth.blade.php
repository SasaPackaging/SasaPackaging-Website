{{-- Frame for pages shown before login (login screen). --}}
<!DOCTYPE html>
<html lang="en">
<head>
  @include('admin.partials.head')
  <link rel="stylesheet" href="@adminAsset('css/auth.css')">
</head>
<body class="adm">
  <main class="adm-auth" id="main">
    <div class="adm-auth__card">
      @include('admin.partials.flash')
      @yield('content')
    </div>
  </main>
  <script src="@adminAsset('js/admin.js')"></script>
</body>
</html>
