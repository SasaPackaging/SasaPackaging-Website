<!-- layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="theme-color" content="{{ config('site.theme_color') }}">

	{{-- SEO: pages pass $title, $keywords, $description, $og_image (same as the old layout) --}}
	@php
		$seo_title = trim((string) ($title ?? '')) !== '' ? strip_tags((string) $title) : config('site.name');
		$seo_desc  = trim(strip_tags((string) ($description ?? '')));
	@endphp
	@section('title')
	<title>{{ $seo_title }}</title>
	@show
	@if ($seo_desc !== '')<meta name="description" content="{{ $seo_desc }}">@endif
	@if (!empty($keywords))<meta name="keywords" content="{{ strip_tags((string) $keywords) }}">@endif
	<link rel="canonical" href="{{ url()->current() }}">

	<meta property="og:type" content="website">
	<meta property="og:site_name" content="{{ config('site.name') }}">
	<meta property="og:title" content="{{ $seo_title }}">
	@if ($seo_desc !== '')<meta property="og:description" content="{{ $seo_desc }}">@endif
	<meta property="og:url" content="{{ url()->current() }}">
	@if (!empty($og_image))<meta property="og:image" content="{{ $og_image }}">@endif
	<meta name="twitter:card" content="summary_large_image">

	<link rel="shortcut icon" href="{{ url('/public/assets/main/images/favicon.png') }}" type="image/x-icon">
	<link rel="icon" href="{{ url('/public/assets/main/images/favicon.png') }}" type="image/x-icon">

	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&amp;family=Inter:wght@400;500;600;700&amp;family=Outfit:wght@500;600;700&amp;display=swap" rel="stylesheet">

	{{-- TRANSITION: the old header/footer/sections still use these two files.
	     When navbar, footer and every section are converted to Tailwind, delete both lines. --}}
	<link href="{{ url('/public/assets/main/css/sasa-ui.css') }}" rel="stylesheet">
	<link href="{{ url('/public/assets/main/css/sasa-premium.css') }}" rel="stylesheet">
	<link href="{{ url('/') }}/public/assets/main/css/sasa-nav.css" rel="stylesheet">

	{{-- Tailwind (built with: npm run build:css) --}}
	<link href="{{ url('/public/assets/main/css/tailwind-generated.css') }}" rel="stylesheet">
		{{-- Hero slider look (must stay AFTER the css above) --}}
	<link href="{{ url('/public/assets/main/css/sasa-hero.css') }}?v=4" rel="stylesheet">
	<link href="{{ url('/public/assets/main/css/sasa-about.css') }}?v=1" rel="stylesheet">
	<link href="{{ url('/public/assets/main/css/sasa-values.css') }}?v=1" rel="stylesheet">
	<link href="{{ url('/public/assets/main/css/sasa-services.css') }}?v=2" rel="stylesheet">
	<link href="{{ url('/public/assets/main/css/sasa-stats.css') }}?v=1" rel="stylesheet">

	@section('customstyle')
	@show
</head>
<body class="sx-site bg-ivory font-sans text-body antialiased">

	<div class="page-wrapper">
		<a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', config('site.whatsapp')) }}" class="float" target="_blank" rel="noopener" aria-label="Chat with us on WhatsApp">
			<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.45 1.32 4.95L2 22l5.27-1.38a9.9 9.9 0 0 0 4.77 1.22h.01c5.46 0 9.91-4.45 9.91-9.91A9.85 9.85 0 0 0 12.04 2zm0 18.15h-.01a8.2 8.2 0 0 1-4.18-1.15l-.3-.18-3.12.82.83-3.04-.2-.31a8.2 8.2 0 0 1-1.26-4.38c0-4.54 3.7-8.23 8.24-8.23a8.2 8.2 0 0 1 8.23 8.24c0 4.54-3.7 8.23-8.23 8.23zm4.52-6.16c-.25-.12-1.47-.72-1.7-.81-.23-.08-.39-.12-.56.13-.17.25-.64.81-.78.97-.14.17-.29.19-.54.06-.25-.12-1.05-.39-2-1.23-.74-.66-1.24-1.47-1.38-1.72-.14-.25-.02-.38.11-.5.11-.11.25-.29.37-.43.12-.15.17-.25.25-.41.08-.17.04-.31-.02-.43-.06-.12-.56-1.34-.76-1.84-.2-.48-.4-.42-.56-.42h-.48c-.17 0-.43.06-.66.31-.23.25-.87.85-.87 2.07 0 1.22.89 2.4 1.01 2.56.12.17 1.75 2.67 4.23 3.74.59.26 1.05.41 1.41.52.59.19 1.13.16 1.56.1.48-.07 1.47-.6 1.67-1.18.21-.58.21-1.07.14-1.18-.06-.1-.23-.16-.48-.29z"/></svg>
		</a>

		@section('header')
		@show

		@yield('content')

		@section('footer')
		@show
	</div>

	{{-- Same section names as the old layout, so a page switches layouts by changing one line. --}}
	<script>
		var path_url = '{{ url('/') }}';
		var path = '{{ url('/') }}';
	</script>
	<script src="{{ url('/public/assets/main/js/sasa-premium.js') }}"></script>
	<script src="{{ url('/public/assets/main/js/sasa-hero.js') }}?v=4"></script>
	<script src="{{ url('/public/assets/main/js/sasa-about.js') }}?v=2"></script>
	<script src="{{ url('/public/assets/main/js/sasa-values.js') }}?v=1"></script>
	<script src="{{ url('/public/assets/main/js/sasa-services.js') }}?v=1"></script>
	<script src="{{ url('/public/assets/main/js/sasa-stats.js') }}?v=1"></script>
	<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

	@section('customscript')
	@show
</body>
</html>
