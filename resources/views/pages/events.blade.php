{{-- pages/events.blade.php  (Events page: /events.html)
     Top: the latest event (or the one chosen with ?event=slug) with its picture(s).
     Below: earlier events with their pictures and videos.
     Needs: $featured (NewsEvent|null), $previous (collection), $page (config news.events_page)
     Look: public/assets/main/css/sasa-events-page.css --}}
@extends('layouts.master')

@section('customstyle')
<link href="{{ url('/') }}/public/assets/main/css/sasa-inner.css" rel="stylesheet">
<link href="{{ url('/') }}/public/assets/main/css/sasa-inner-about.css" rel="stylesheet">
<link href="{{ url('/') }}/public/assets/main/css/sasa-events-page.css" rel="stylesheet">
@stop

@section('header')
@include('partial.header')
@stop

@section('content')
@include('pages.inner._about_helpers')
<main class="sxi sxa evp" id="main">

	@if ($featured)
		@include('pages.inner._about_hero', [
			'h_crumbs'  => [[$page['crumb_home'], url('/')], [$page['title'], null]],
			'h_eyebrow' => $page['eyebrow_latest'],
			'h_title'   => $featured->title,
			'h_lead'    => $featured->summary,
			'h_imgs'    => array_slice($featured->image_urls, 0, 3),
			'h_kind'    => 'default',
			'h_badge'   => trim($featured->shown_date->format('d M Y') . ($featured->location ? ' · ' . $featured->location : '')),
		])

		<section class="sxi-section sxa-body">
			<div class="sxi-wrap sxa-article">
				<article class="sxa-card">
					@include('pages._event-body', ['e' => $featured, 'page' => $page, 'gallery' => true])
				</article>
			</div>
		</section>
	@else
		@include('pages.inner._about_hero', [
			'h_crumbs'  => [[$page['crumb_home'], url('/')], [$page['title'], null]],
			'h_eyebrow' => $page['title'],
			'h_title'   => $page['empty_title'],
			'h_lead'    => $page['empty_text'],
			'h_imgs'    => [],
			'h_kind'    => 'default',
			'h_badge'   => $page['hero_badge'],
		])
	@endif

	@if ($previous->count())
	<section class="sxi-section" style="padding-top:0">
		<div class="sxi-wrap">
			<header class="evp-head">
				<h2>{{ $page['previous_heading'] }}</h2>
				@if ($page['previous_lead'])<p>{{ $page['previous_lead'] }}</p>@endif
			</header>

			<div class="evp-list">
				@foreach ($previous as $e)
				<article class="evp-event" id="event-{{ $e->slug }}">
					@include('pages._event-body', ['e' => $e, 'page' => $page, 'gallery' => true, 'link' => true])
				</article>
				@endforeach
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
