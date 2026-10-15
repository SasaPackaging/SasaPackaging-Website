{{-- pages\about.blade.php --}}
@extends('layouts.master')

@section('customstyle')
<link href="{{ url('/') }}/public/assets/main/css/sasa-inner.css" rel="stylesheet">
<link href="{{ url('/') }}/public/assets/main/css/sasa-inner-about.css" rel="stylesheet">
@stop

@section('header')
@include('partial.header')
@stop

@section('content')
@include('pages.inner._about_helpers')
@php
$cid  = (int) $cms_dp['id'];
$nav  = sxa_nav($cid);
$row  = $nav['row'];
$slug = $nav['slug'] ?: 'about-us';

/* images: about-images/{slug}.jpg, {slug}-2.jpg ...  (+ Admin upload as the first one if no file) */
$imgs = [];
$main = sxa_img($slug, $row['image'] ?? '');
if ($main !== '') { $imgs[] = $main; }
foreach (sxa_extra($slug) as $u) { $imgs[] = $u; }
$hero_imgs = array_slice($imgs, 0, 3);
$gallery   = array_slice($imgs, 3);

$title = trim($cms_dp['banner_heading'] ?? '') !== '' ? $cms_dp['banner_heading'] : $cms_dp['name'];
$tag   = trim(strip_tags($cms_dp['tag_line'] ?? ''));
$lead  = sxa_plain($cms_dp['short_contents'] ?? '', 200);
if ($lead === '') { $lead = ($tag !== '' && $tag !== $title) ? $tag : sxa_plain($cms_dp['full_contents'] ?? '', 190); }
$has_text = trim(strip_tags(html_entity_decode((string) ($cms_dp['full_contents'] ?? '')))) !== '';
/* story text from Admin, cleaned so it shows as normal full-width paragraphs */
$story_html = sxa_clean($cms_dp['full_contents'] ?? '');
$others = array_values(array_filter($nav['sibs'], function ($s) use ($cid) { return (int) $s['id'] !== $cid && !in_array($s['slug'], ['about-us', 'about-sasa-packaging'], true); }));
@endphp

<main class="sxi sxa" id="main">
	@include('pages.inner._about_hero', ['h_crumbs' => [['Home', url('/')], [$cms_dp['name'], null]], 'h_eyebrow' => 'About us', 'h_title' => $title, 'h_lead' => $lead, 'h_imgs' => $hero_imgs, 'h_kind' => 'about', 'h_cta' => ['Our story', '#story']])

	@include('pages.inner._about_nav', ['nav' => $nav, 'cur_id' => $cid])

	{{-- story (full text from Admin > Pages) --}}
	<section class="sxi-section" id="story">
		<div class="sxi-wrap">
			<div class="sxi-secthead">
				<div><span class="sxi-eyebrow">{{ $tag !== '' ? $tag : 'Our story' }}</span><h2>{{ trim($cms_dp['heading'] ?? '') !== '' ? $cms_dp['heading'] : 'Who we are' }}</h2></div>
			</div>
			<div class="sxa-card sxa-card--wide sxi-reveal">
				@if($has_text)<div class="sxi-prose sxa-prose" data-prose>{!! $story_html !!}</div>@else<div class="sxi-prose"><p>Our story will be added soon.</p></div>@endif
			</div>
		</div>
	</section>

	@if(count($gallery) > 0)
	<section class="sxi-section sxi-section--white">
		<div class="sxi-wrap">
			<div class="sxi-secthead"><div><span class="sxi-eyebrow">Inside SASA</span><h2>A closer look</h2></div></div>
			<div class="sxa-gal">
				@foreach($gallery as $gi => $gu)
					<a class="sxa-gal__i sxi-reveal" style="--d:{{ ($gi % 4) * 80 }}ms" href="{{ $gu }}" data-gal-open data-src="{{ $gu }}"><img src="{{ $gu }}" alt="" loading="lazy"></a>
				@endforeach
			</div>
		</div>
	</section>
	@endif

	{{-- what drives us: the other About pages --}}
	@if(count($others) > 0)
	<section class="sxi-section sxi-section--blush">
		<div class="sxi-wrap">
			<div class="sxi-secthead">
				<div><span class="sxi-eyebrow">What drives us</span><h2>Discover more</h2></div>
				<p>Our purpose, our standards and the way we work, in a little more detail.</p>
			</div>
			<div class="sxa-cards">
				@foreach($others as $oi => $o)
				@php
					$ok = sxa_kind($o['slug'], $o['name']);
					$ob = sxa_plain($o['short_contents'] ?? '', 120);
					if ($ob === '') { $ob = sxa_plain($o['tag_line'] ?? '', 120); }
					if ($ob === '') { $ob = sxa_plain($o['full_contents'] ?? '', 120); }
				@endphp
				<a class="sxa-lc sxi-reveal sxi-c{{ $oi % 6 }}" style="--d:{{ ($oi % 4) * 80 }}ms" href="{{ url('/' . $o['slug'] . '.html') }}">
					<i>{!! sxa_icon($ok) !!}</i>
					<h3>{{ $o['name'] }}</h3>
					@if($ob !== '')<p>{{ $ob }}</p>@endif
					<span class="sxa-lc__go">Read more <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg></span>
				</a>
				@endforeach
			</div>
		</div>
	</section>
	@endif

	<section class="sxi-section">
		<div class="sxi-wrap">
			<div class="sxi-cta sxi-reveal">
				<div><h2>Let's make something great together</h2><p>Tell us about your packaging and we will send you a quote.</p></div>
				<div class="sxi-cta__btns">
					<a class="sxi-btn sxi-btn--solid" href="{{ url('/') }}/get-quote.html">Get a Quote <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg></a>
					<a class="sxi-btn sxi-btn--ghost" href="{{ url('/') }}/contact-us.html">Contact us</a>
				</div>
			</div>
		</div>
	</section>

	<div class="sxi-lb" data-lb role="dialog" aria-modal="true" aria-label="Image viewer">
		<button type="button" class="sxi-lb__x" data-lb-x aria-label="Close">&times;</button>
		<img src="" alt="">
	</div>
</main>
@stop

@section('footer')
@include('partial.footer')
@stop

@section('customscript')
<script src="{{ url('/') }}/public/assets/main/js/sasa-inner-about.js"></script>
<script src="{{ url('/') }}/public/assets/main/js/sasa-inner.js"></script>
@stop