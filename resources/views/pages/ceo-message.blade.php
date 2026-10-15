<!-- pages\ceo-message.blade.php -->
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
$slug = $nav['slug'] ?: 'ceo-message';
/* portrait: about-images/ceo-message.jpg (or ceo.jpg) -> Admin upload -> themed tile */
$portrait = sxa_img($slug, $row['image'] ?? '');
if ($portrait === '') { $portrait = sxa_img('ceo', ''); }

$title = trim($cms_dp['heading'] ?? '') !== '' ? $cms_dp['heading'] : $cms_dp['name'];
$role  = trim(strip_tags($cms_dp['tag_line'] ?? ''));
$html  = (string) ($cms_dp['full_contents'] ?? '');
$plain = sxa_plain($html, 100000);
/* pull-quote = first sentence(s) of the message, up to ~170 characters */
$quote = '';
if ($plain !== '') {
	$quote = $plain;
	if (preg_match('/^(.{40,170}?[.!?])(\s|$)/u', $plain, $m)) { $quote = $m[1]; } else { $quote = mb_strimwidth($plain, 0, 170, '…'); }
}
$crumbs = [['Home', url('/')]];
if ($nav['parent'] !== '' && $nav['parent'] !== $cms_dp['name']) { $crumbs[] = [$nav['parent'], null]; }
$crumbs[] = [$cms_dp['name'], null];
$social = trim((string) ($cms_dp['short_contents'] ?? ''));
@endphp

<main class="sxi sxa" id="main">
	<section class="sxi-hero sxa-ceo-hero">
		<span class="sxi-float sxi-float--a" data-depth="26"></span>
		<span class="sxi-float sxi-float--b" data-depth="-18"></span>
		<div class="sxi-wrap">
			<ul class="sxi-crumbs">
				@foreach($crumbs as $cr)<li>@if($cr[1])<a href="{{ $cr[1] }}">{{ $cr[0] }}</a>@else{{ $cr[0] }}@endif</li>@endforeach
			</ul>

			<div class="sxa-ceo">
				<div class="sxa-ceo__pic">
					<div class="sxa-ring" aria-hidden="true"></div>
					<div class="sxa-ring sxa-ring--2" aria-hidden="true"></div>
					<div class="sxa-portrait sxi-c0" data-depth="16">
						@if($portrait !== '')<img src="{{ $portrait }}" alt="{{ $title }}" onerror="this.nextElementSibling.classList.add('is-on');this.remove()">@endif
						<span class="sxi-ph sxa-ph-big {{ $portrait === '' ? 'is-on' : '' }}">{!! sxa_icon('ceo') !!}</span>
					</div>
					<div class="sxa-nameplate">
						<strong>{{ $title }}</strong>
						<small>{{ $role !== '' ? $role : 'Chief Executive Officer' }}</small>
					</div>
					@if($social !== '')<div class="sxa-social">{!! $social !!}</div>@endif
				</div>

				<div class="sxa-ceo__txt">
					<span class="sxi-eyebrow">{{ $nav['parent'] ?: 'Leadership' }} · Message</span>
					<h1 class="sxi-h1" data-split>A message from our CEO</h1>
					@if($quote !== '')
					<blockquote class="sxa-pull sxi-reveal">
						<svg class="sxa-pull__q" viewBox="0 0 48 48" aria-hidden="true"><path d="M10 28c0-8 4-14 12-16l1.6 3C19 17 17.5 20 17.3 23H22v13H10zM28 28c0-8 4-14 12-16l1.6 3C37 17 35.5 20 35.3 23H40v13H28z" fill="currentColor"/></svg>
						<p>{{ $quote }}</p>
					</blockquote>
					@endif
				</div>
			</div>
		</div>
	</section>

	@include('pages.inner._about_nav', ['nav' => $nav, 'cur_id' => $cid])

	<section class="sxi-section">
		<div class="sxi-wrap sxa-narrow">
			<article class="sxa-letter">
				<div class="sxa-letter__bar" aria-hidden="true"><span></span></div>
				<div class="sxi-prose sxa-dropcap" data-prose>{!! $html !!}</div>
				<footer class="sxa-sign sxi-reveal">
					<span class="sxa-sign__line" aria-hidden="true"></span>
					<strong>{{ $title }}</strong>
					<small>{{ $role !== '' ? $role : 'Chief Executive Officer' }}, SASA Packaging</small>
				</footer>
			</article>
		</div>
	</section>

	@include('pages.inner._about_prevnext', ['nav' => $nav])
</main>
@stop

@section('footer')
@include('partial.footer')
@stop

@section('customscript')
<script src="{{ url('/') }}/public/assets/main/js/sasa-inner-about.js"></script>
<script src="{{ url('/') }}/public/assets/main/js/sasa-inner.js"></script>
@stop
