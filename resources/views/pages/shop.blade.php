{{-- pages/shop.blade.php  (Shop Now: /shop.html)
     Needs: $products (paginator), $categories (collection), $category (string), $page (config shop.page)
     Look: public/assets/main/css/sasa-shop.css   Script: public/assets/main/js/sasa-shop.js --}}
@extends('layouts.master')

@section('customstyle')
<link href="{{ url('/') }}/public/assets/main/css/sasa-shop.css" rel="stylesheet">
@if(!empty($og_image))<meta property="og:image" content="{{ $og_image }}">@endif
@stop

@section('header')
@include('partial.header')
@stop

@section('content')
<main class="shp" id="main">

	<section class="shp-hero">
		<div class="shp-wrap">
			<nav class="shp-crumbs" aria-label="Breadcrumb"><a href="{{ url('/') }}">Home</a><span>{{ $page['title'] }}</span></nav>
			<p class="shp-eyebrow">{{ $page['eyebrow'] }}</p>
			<h1>{{ $page['title'] }}</h1>
			@if($page['lead'])<p class="shp-lead">{{ $page['lead'] }}</p>@endif

			@if($categories->count() > 1)
			<div class="shp-chips" role="navigation" aria-label="Product categories">
				<a class="shp-chip {{ $category === '' ? 'is-active' : '' }}" href="{{ url(config('shop.url')) }}">{{ $page['all_label'] }}</a>
				@foreach($categories as $name)
					<a class="shp-chip {{ $category === $name ? 'is-active' : '' }}" href="{{ url(config('shop.url')) }}?category={{ rawurlencode($name) }}">{{ $name }}</a>
				@endforeach
			</div>
			@endif
		</div>
	</section>

	<section class="shp-section">
		<div class="shp-wrap">
			@if($products->count())
				<div class="shp-grid">
					@foreach($products as $p)
						@include('pages._shop-card', ['p' => $p, 'page' => $page])
					@endforeach
				</div>

				@if($products->hasPages())
				<nav class="shp-pager" aria-label="Pages">
					@if($products->onFirstPage())<span class="shp-btn shp-btn--ghost is-off">&larr; Previous</span>@else<a class="shp-btn shp-btn--ghost" href="{{ $products->previousPageUrl() }}" rel="prev">&larr; Previous</a>@endif
					<span class="shp-pager__info">Page {{ $products->currentPage() }} of {{ $products->lastPage() }}</span>
					@if($products->hasMorePages())<a class="shp-btn shp-btn--ghost" href="{{ $products->nextPageUrl() }}" rel="next">Next &rarr;</a>@else<span class="shp-btn shp-btn--ghost is-off">Next &rarr;</span>@endif
				</nav>
				@endif
			@else
				<div class="shp-empty">
					<h2>{{ $page['empty_title'] }}</h2>
					<p>{{ $page['empty_text'] }}</p>
					<a class="shp-btn" href="{{ url('/get-quote.html') }}">{{ $page['quote_label'] }}</a>
				</div>
			@endif
		</div>
	</section>
</main>
@stop

@section('footer')
@include('partial.footer')
@stop

@section('customscript')
<script src="{{ url('/') }}/public/assets/main/js/sasa-shop.js"></script>
@stop
