{{-- pages/shop-product.blade.php  (/shop/{slug})
     Needs: $product (ShopProduct), $related (collection), $page (config shop.page) --}}
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
	<section class="shp-section shp-section--first">
		<div class="shp-wrap">
			<nav class="shp-crumbs" aria-label="Breadcrumb">
				<a href="{{ url('/') }}">Home</a>
				<a href="{{ url(config('shop.url')) }}">{{ $page['title'] }}</a>
				<span>{{ $product->name }}</span>
			</nav>

			<div class="shp-detail">
				<div class="shp-gallery" data-shp-gallery>
					<div class="shp-gallery__main"><img src="{{ $product->image_url }}" alt="{{ $product->name }}" data-shp-main></div>
					@if(count($product->image_urls) > 1)
					<div class="shp-gallery__thumbs">
						@foreach($product->image_urls as $i => $url)
							<button type="button" class="{{ $i === 0 ? 'is-active' : '' }}" data-shp-thumb="{{ $url }}" aria-label="Picture {{ $i + 1 }}"><img src="{{ $url }}" alt="" loading="lazy"></button>
						@endforeach
					</div>
					@endif
				</div>

				<div class="shp-info">
					@if($product->category)<p class="shp-eyebrow">{{ $product->category }}</p>@endif
					<h1>{{ $product->name }}</h1>
					<p class="shp-detail__price {{ $product->has_price ? '' : 'is-ask' }}">{{ $product->price_label }}</p>
					@if($product->min_order)<p class="shp-detail__moq">{{ $product->min_order }}</p>@endif
					@if($product->summary)<p class="shp-lead">{{ $product->summary }}</p>@endif

					<div class="shp-detail__actions">
						<a class="shp-btn" href="{{ $product->order_url }}" target="_blank" rel="noopener">{{ $page['order_label'] }}</a>
						<a class="shp-btn shp-btn--ghost" href="{{ url('/get-quote.html') }}">{{ $page['quote_label'] }}</a>
					</div>

					@if($product->description)<div class="shp-prose">{!! $product->description !!}</div>@endif
				</div>
			</div>
		</div>
	</section>

	@if($related->count())
	<section class="shp-section">
		<div class="shp-wrap">
			<h2 class="shp-h2">You may also like</h2>
			<div class="shp-grid shp-grid--related">
				@foreach($related as $p)
					@include('pages._shop-card', ['p' => $p, 'page' => $page])
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
<script src="{{ url('/') }}/public/assets/main/js/sasa-shop.js"></script>
@stop
