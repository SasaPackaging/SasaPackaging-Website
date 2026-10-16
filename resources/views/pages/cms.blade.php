{{-- pages\cms.blade.php  (Mission, Vision, Core Values, Strategy, Certificates and other CMS pages) --}}
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
$cid  = (int) ($cms_dp['id'] ?? 0);
$nav  = sxa_nav($cid);
$row  = $nav['row'];
$slug = $nav['slug'];
$kind = sxa_kind($slug, $cms_dp['name'] ?? '');
$is_cert = ($cid === 20) || ($kind === 'certificates');
$is_404  = ($cid === 0);

$imgs = [];
if (!$is_404) {
	$main = sxa_img($slug, $row['image'] ?? '');
	if ($main === '') { $main = sxa_img($slug, $row['banner'] ?? ''); }
	if ($main !== '') { $imgs[] = $main; }
	foreach (sxa_extra($slug) as $u) { $imgs[] = $u; }
}
$title = trim($cms_dp['heading'] ?? '') !== '' ? $cms_dp['heading'] : ($cms_dp['name'] ?? '');
$title = sxa_title($title);   /* removes <br> and other tags that Admin headings may contain */
$tag   = trim(strip_tags($cms_dp['tag_line'] ?? ''));
$has_text = trim(strip_tags(html_entity_decode((string) ($cms_dp['full_contents'] ?? '')))) !== '';
$lead  = ($tag !== '' && $tag !== $title) ? $tag : sxa_plain($cms_dp['full_contents'] ?? '', 190);
$certs = $is_cert ? sxa_certs() : [];

$crumbs = [['Home', url('/')]];
if ($nav['parent'] !== '' && $nav['parent'] !== ($cms_dp['name'] ?? '')) { $crumbs[] = [$nav['parent'], null]; }
$crumbs[] = [$cms_dp['name'] ?? 'Page', null];
@endphp

<main class="sxi sxa" id="main">
	@include('pages.inner._about_hero', ['h_crumbs' => $crumbs, 'h_eyebrow' => $is_404 ? 'Error 404' : ($nav['parent'] ?: 'SASA Packaging'), 'h_title' => $is_404 ? 'Page not found' : $title, 'h_lead' => $is_404 ? 'Sorry, the page you are looking for was not found.' : $lead, 'h_imgs' => $imgs, 'h_kind' => $kind])

	@if($is_404)
		<section class="sxi-section"><div class="sxi-wrap" style="text-align:center">
			<a class="sxi-btn sxi-btn--dark" href="{{ url('/') }}">Back to home</a>
			<a class="sxi-btn sxi-btn--line" href="{{ url('/products-and-services.html') }}">Browse products</a>
		</div></section>
	@else
		@include('pages.inner._about_nav', ['nav' => $nav, 'cur_id' => $cid])

		@if($is_cert)
			@if($has_text)
			<section class="sxi-section"><div class="sxi-wrap sxa-narrow"><div class="sxi-prose" data-prose>{!! $cms_dp['full_contents'] !!}</div></div></section>
			@endif
			@include('pages.inner._certificates', ['certs' => $certs])
		@else
			{{-- the top pill menu is the only section menu, so the text card uses the full width --}}
			<section class="sxi-section sxa-body">
				<div class="sxi-wrap sxa-article">
					<article class="sxa-card sxi-reveal">
						<div class="sxa-card__kicker"><i>{!! sxa_icon($kind) !!}</i><span>{{ $cms_dp['name'] }}</span></div>
						@if($has_text)
							<div class="sxi-prose sxa-prose" data-prose>{!! $cms_dp['full_contents'] !!}</div>
						@else
							<div class="sxi-prose"><p>Details for this page will be added soon.</p></div>
						@endif
					</article>
				</div>
			</section>
		@endif

		@include('pages.inner._about_prevnext', ['nav' => $nav])

		<section class="sxi-section" style="padding-top:0">
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
	@endif
</main>
@stop

@section('footer')
@include('partial.footer')
@stop

@section('customscript')
<script src="{{ url('/') }}/public/assets/main/js/sasa-inner-about.js"></script>
<script src="{{ url('/') }}/public/assets/main/js/sasa-inner.js"></script>
@stop