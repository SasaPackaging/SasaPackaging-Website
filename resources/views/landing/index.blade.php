<!--landing/index.blade.php -->
@extends('layouts.app')

@section('customstyle')
@stop

@section('header')
@include('partial.header')
@stop

@section('content')

@php
/* ======================================================================
   DATA for the whole home page (same models/queries as before).
   Every section file below reads these variables — nothing is queried
   inside the section files. Wording comes from config/landing.php.
   ====================================================================== */
$banners          = App\Model\Banners::whereRaw("status = 'Yes'")->orderByRaw('sort_order')->get()->toArray();
$features_dp      = App\Model\Services::whereRaw("status = 'Yes' ")->orderByRaw('sort_order')->get()->toArray();
$data_paper       = App\Model\Cms::whereRaw(" status = 'Yes' AND id=7 ")->get()->toArray();      // lamination block
$data_printer     = App\Model\Cms::whereRaw(" status = 'Yes' AND id=8 ")->get()->toArray();      // about block
$db_category      = App\Model\Category::whereRaw("status = 'Yes' AND is_featured = 'Yes' ")->orderByRaw('sort_order')->get()->toArray();
$members_dp       = App\Model\Members::whereRaw("status = 'Yes'  ")->orderByRaw('sort_order')->get()->toArray();
$certificate_page = App\Model\Cms::whereRaw("status = 'Yes' AND id = 20")->first();
$data_services    = App\Model\Cms::whereRaw(" status = 'Yes' AND p_id=24 ")->orderByRaw('sort_order')->get()->toArray();

// News: wrapped so a missing table can never break the home page.
$sx_news = [];
try {
	$sx_news = App\Model\Posts::where('status', 'Yes')->orderBy('post_date', 'desc')->limit((int) config('landing.news.limit', 3))->get()->toArray();
} catch (\Throwable $ex) {
	$sx_news = [];
}

// Stats: real numbers counted from the database (only shown when > 0).
$sx_stats = [];
try {
	$labels = config('landing.stats.labels');
	$founded = (int) config('landing.stats.founded_year');
	if ($founded > 1900 && $founded <= (int) date('Y')) {
		$sx_stats[] = ['icon' => 'calendar', 'value' => (int) date('Y') - $founded, 'label' => $labels['years']];
	}
	$sx_stats[] = ['icon' => 'cube',  'value' => App\Model\Products::whereRaw("status = 'Yes'")->count(), 'label' => $labels['products']];
	$sx_stats[] = ['icon' => 'sliders', 'value' => App\Model\Category::whereRaw("status = 'Yes' AND pid = 0")->count(), 'label' => $labels['categories']];
	$sx_stats[] = ['icon' => 'badge', 'value' => count($members_dp), 'label' => $labels['partners']];
	$sx_stats = array_values(array_filter($sx_stats, function ($s) { return $s['value'] > 0; }));
} catch (\Throwable $ex) {
	$sx_stats = [];
}

// Helpers shared by the section files.
// Turns "/about-us.html" or "#machines" into a full URL; leaves http(s)/tel/mailto links alone.
$sx_link = function ($link) {
	$link = (string) $link;
	if ($link === '') { return ''; }
	if (preg_match('~^(https?:|mailto:|tel:)~i', $link)) { return $link; }
	if ($link[0] === '#') { return url('/') . $link; }
	return url($link);
};
// Safe rich text: allow only basic inline tags, keep line breaks.
$sx_rich = function ($text) {
	return nl2br(strip_tags((string) $text, '<br><strong><em><b><i><span>'));
};
@endphp

@include('landing.hero')
@include('landing.about')
@include('landing.values')
@include('landing.services')
@include('landing.stats')
@include('landing.lamination')
@include('landing.products')
@include('landing.certifications')
@include('landing.news')
@include('landing.testimonials')

@stop

@section('footer')
@include('partial.footer')
@stop

@section('customscript')
<script>
/* Stats: count up when the section scrolls into view (numbers already in the HTML if JS is off). */
(function () {
	var els = document.querySelectorAll('[data-sx-count]');
	if (!els.length) { return; }
	var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
	if (reduce || !('IntersectionObserver' in window)) { return; }
	var run = function (el) {
		var end = parseInt(el.getAttribute('data-sx-count'), 10) || 0, t0 = null, dur = 1400;
		var step = function (t) {
			if (t0 === null) { t0 = t; }
			var p = Math.min((t - t0) / dur, 1);
			el.textContent = Math.round(end * (1 - Math.pow(1 - p, 3)));
			if (p < 1) { requestAnimationFrame(step); }
		};
		requestAnimationFrame(step);
	};
	var io = new IntersectionObserver(function (entries) {
		entries.forEach(function (en) { if (en.isIntersecting) { run(en.target); io.unobserve(en.target); } });
	}, { threshold: 0.4 });
	els.forEach(function (el) { io.observe(el); });
})();
</script>
@stop
