@extends('layouts.master')

@section('customstyle')
<link href="{{ url('/') }}/public/assets/main/css/sasa-inner.css" rel="stylesheet">
@stop

@section('header')
@include('partial.header')
@stop

@section('content')
@include('pages.inner._helpers')
@php
$sxi_cat   = App\Model\Category::find($cms_dp['id']);
$sxi_cat   = $sxi_cat ? $sxi_cat->toArray() : ['slug' => '', 'image' => '', 'name' => $cms_dp['name']];
$sxi_prods = App\Model\Products::whereRaw("status = 'Yes' AND category_id = " . (int) $cms_dp['id'])->orderByRaw('sort_order')->get()->toArray();
$sxi_others = App\Model\Category::whereRaw("status = 'Yes' AND pid = 0")->orderByRaw('sort_order')->get()->toArray();

$sxi_hero_img = sxi_img($sxi_cat['slug'] ?? '', $sxi_cat['image'] ?? '', 'category');
if ($sxi_hero_img === '' && count($sxi_prods) > 0) { $sxi_hero_img = sxi_img($sxi_prods[0]['slug'], $sxi_prods[0]['image'], 'products'); }
$sxi_lead = sxi_plain($cms_dp['full_contents'] ?? '', 260);
if ($sxi_lead === '') { $sxi_lead = 'Made-to-order ' . strtolower($cms_dp['name']) . ' in your size, finish and branding.'; }
@endphp

<main class="sxi" id="main">

	<section class="sxi-hero">
		<span class="sxi-float sxi-float--a" data-depth="26"></span>
		<span class="sxi-float sxi-float--b" data-depth="-18"></span>
		<span class="sxi-float sxi-float--c" data-depth="34"></span>
		<div class="sxi-wrap sxi-hero__grid">
			<div>
				<ul class="sxi-crumbs">
					<li><a href="{{ url('/') }}">Home</a></li>
					<li><a href="{{ url('/products-and-services.html') }}">Products &amp; Services</a></li>
					<li>{{ $cms_dp['name'] }}</li>
				</ul>
				<span class="sxi-eyebrow">Collection</span>
				<h1 class="sxi-h1" data-split>{{ $cms_dp['heading'] != '' ? $cms_dp['heading'] : $cms_dp['name'] }}</h1>
				<p class="sxi-lead">{{ $sxi_lead }}</p>
				<ul class="sxi-stats">
					<li><b>{{ count($sxi_prods) }}</b> {{ count($sxi_prods) == 1 ? 'Product' : 'Products' }}</li>
					<li>Made to order</li>
				</ul>
				<div class="sxi-actions" style="margin-top:26px">
					<a class="sxi-btn sxi-btn--dark" href="#items">View products <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M6 13l6 6 6-6"/></svg></a>
					<a class="sxi-btn sxi-btn--line" href="{{ url('/') }}/get-quote.html">Get a Quote</a>
				</div>
			</div>
			<div class="sxi-collage" aria-hidden="true">
				<div class="sxi-arch sxi-arch--solo sxi-c0" data-depth="18">
					@if($sxi_hero_img !== '')<img src="{{ $sxi_hero_img }}" alt="" onerror="this.nextElementSibling.classList.add('is-on');this.remove()">@endif
					<span class="sxi-ph {{ $sxi_hero_img === '' ? 'is-on' : '' }}">{!! sxi_icon() !!}</span>
				</div>
				<div class="sxi-badge"><i><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3l2.6 5.6 6.1.7-4.5 4.2 1.2 6L12 16.5 6.6 19.5l1.2-6L3.3 9.3l6.1-.7z"/></svg></i><span><b>{{ $cms_dp['name'] }}</b>Premium finish</span></div>
			</div>
		</div>
	</section>

	<section class="sxi-section sxi-section--white" id="items">
		<div class="sxi-wrap">
			<div class="sxi-secthead">
				<div><span class="sxi-eyebrow">{{ $cms_dp['name'] }}</span><h2>Choose your style</h2></div>
				<p>Select a product to see photos, details and to request a quote.</p>
			</div>

			@if(count($sxi_prods) > 0)
			<div class="sxi-grid" data-feature>
				@foreach($sxi_prods as $pi => $prow)
					@include('pages.inner._tile', ['row' => $prow, 'i' => $pi, 'kind' => 'product', 'tag' => $cms_dp['name'], 'feature' => ($pi === 0 && count($sxi_prods) > 3)])
				@endforeach
			</div>
			@else
			<div class="sxi-empty sxi-empty--static">
				<svg class="sxi-empty__ico" viewBox="0 0 64 64" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"><path d="M32 6 56 18v28L32 58 8 46V18z"/><path d="M8 18l24 12 24-12M32 30v28"/></svg>
				<p>Products for this collection are coming soon.</p>
			</div>
			@endif
		</div>
	</section>

	@if(count($sxi_others) > 1)
	<section class="sxi-section sxi-section--blush">
		<div class="sxi-wrap">
			<div class="sxi-secthead"><div><span class="sxi-eyebrow">Keep exploring</span><h2>Other Collections</h2></div></div>
			<ul class="sxi-pills">
				@foreach($sxi_others as $o)
					<li><a href="{{ url('/' . $o['slug'] . '.html') }}" class="{{ $o['id'] == $cms_dp['id'] ? 'is-current' : '' }}">{{ $o['name'] }}</a></li>
				@endforeach
			</ul>
		</div>
	</section>
	@endif

	<section class="sxi-section">
		<div class="sxi-wrap">
			<div class="sxi-cta sxi-reveal">
				<div><h2>Need {{ strtolower($cms_dp['name']) }} with your branding?</h2><p>Share your size and quantity and we will send you a quote.</p></div>
				<div class="sxi-cta__btns">
					<a class="sxi-btn sxi-btn--solid" href="{{ url('/') }}/get-quote.html">Get a Quote <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg></a>
					<a class="sxi-btn sxi-btn--ghost" href="https://wa.me/+923202956541" target="_blank" rel="noopener">WhatsApp us</a>
				</div>
			</div>
		</div>
	</section>
</main>
@stop

@section('footer')
@include('partial.footer')
@stop

@section('customscript')
<script src="{{ url('/') }}/public/assets/main/js/sasa-inner.js"></script>
@stop
