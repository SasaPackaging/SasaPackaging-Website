<!-- pages/machines.blade.php  ->  opens at /machines.html -->
@extends('layouts.app')

@section('customstyle')
<link href="{{ url('/public/assets/main/css/sasa-machines.css') }}?v=1" rel="stylesheet">
@stop

@section('header')
@include('partial.header')
@stop

@section('content')
@php
/* Same data as the landing "Machines" section: config/landing.php > machines
   Real machines = config machines.items  (name, image, desc). Image folder: public/upload/machines/
   While items is empty, soft placeholders are shown instead (same as the landing page). */
$mch_cfg   = (array) config('landing.machines');
$mch_items = \App\Models\Machine::forSite();                  // machines added in Admin > Machines
if (count($mch_items) === 0) {
	$mch_items = (array) ($mch_cfg['items'] ?? []);              // old way: config/landing.php
}
$mch_ph    = false;
if (count($mch_items) === 0) {
	$mch_ph = true;
	for ($i = 0; $i < (int) ($mch_cfg['placeholder_count'] ?? 3); $i++) {
		$mch_items[] = ['name' => $mch_cfg['placeholder_name'] ?? 'Machine name', 'image' => '', 'desc' => $mch_cfg['placeholder_text'] ?? 'Description to be added.'];
	}
}
@endphp

<section class="mch-hero">
	<div class="sx-container">
		@if (!empty($mch_cfg['eyebrow']))<span class="sx-eyebrow">{{ $mch_cfg['eyebrow'] }}</span>@endif
		<h1 class="mch-hero__title">{!! $mch_cfg['title'] ?? 'Machines &amp; Manufacturing' !!}</h1>
		@if (!empty($mch_cfg['lead']))<p class="mch-hero__lead">{!! $mch_cfg['lead'] !!}</p>@endif
	</div>
</section>

<section class="mch-list" data-mch>
	<div class="sx-container">
		@foreach ($mch_items as $k => $m)
		@php
			$name = trim(strip_tags((string) ($m['name'] ?? '')));
			$desc = trim(strip_tags((string) ($m['desc'] ?? '')));
			$file = trim((string) ($m['image'] ?? ''));
			$img  = !empty($m['url']) ? $m['url'] : (($file !== '' && file_exists(public_path('upload/machines/' . $file))) ? url('/public/upload/machines/' . rawurlencode($file)) : '');
		@endphp
		<article class="mch-row{{ $k % 2 ? ' mch-row--flip' : '' }}">
			<figure class="mch-media mch-media--{{ $k % 3 }}{{ $img === '' ? ' is-empty' : '' }}">
				@if ($img !== '')
					<img src="{{ $img }}" alt="{{ $name }}" loading="lazy">
				@else
					<span class="mch-media__ph" aria-hidden="true"><x-site.icon name="sliders" /></span>
				@endif
				<i class="mch-media__sheen" aria-hidden="true"></i>
			</figure>
			<div class="mch-text">
				<span class="mch-text__num" aria-hidden="true">{{ str_pad($k + 1, 2, '0', STR_PAD_LEFT) }}</span>
				<h2 class="mch-text__name">{{ $name }}</h2>
				@if ($desc !== '')<p class="mch-text__desc">{{ $desc }}</p>@endif
			</div>
		</article>
		@endforeach
	</div>
</section>

<section class="mch-cta">
	<div class="sx-container">
		<h2 class="mch-cta__title">Have a project in mind?</h2>
		<a href="{{ url('/get-quote.html') }}" class="mch-cta__btn">
			<span>Request a Quote</span>
			<i aria-hidden="true"><x-site.icon name="arrow" /></i>
		</a>
	</div>
</section>
@stop

@section('footer')
@include('partial.footer')
@stop

@section('customscript')
<script>
/* rows rise in one by one as they scroll into view (visible by default if JS is off) */
(function () {
	var list = document.querySelector('[data-mch]');
	if (!list) { return; }
	var rows = list.querySelectorAll('.mch-row');
	if (!('IntersectionObserver' in window)) { return; }
	list.classList.add('is-armed');
	var io = new IntersectionObserver(function (en) {
		en.forEach(function (e) { if (e.isIntersecting) { e.target.classList.add('is-in'); io.unobserve(e.target); } });
	}, { threshold: 0.18 });
	[].forEach.call(rows, function (r) { io.observe(r); });
})();
</script>
@stop