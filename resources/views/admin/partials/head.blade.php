{{-- <head> contents shared by the app layout and the login layout --}}
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex, nofollow">
<title>@yield('title', 'Admin') · {{ config('admin.brand') ?: config('site.name') }}</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;family=Outfit:wght@500;600;700&amp;display=swap">
<link rel="stylesheet" href="@adminAsset('css/tokens.css')">
<link rel="stylesheet" href="@adminAsset('css/base.css')">
<link rel="stylesheet" href="@adminAsset('css/layout.css')">
<link rel="stylesheet" href="@adminAsset('css/components.css')">
<link rel="stylesheet" href="@adminAsset('css/content.css')">
<link rel="icon" type="image/svg+xml" href="{{ url('/') }}/public/assets/main/images/logo-small.svg">
