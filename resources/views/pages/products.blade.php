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
/* Data: everything the page needs is read here (controller untouched).
   Categories = the same top-level ones used in the navbar mega-menu. */
$sxi_cats = App\Model\Category::whereRaw("status = 'Yes' AND pid = 0")->orderByRaw('sort_order')->get()->toArray();
$sxi_all  = App\Model\Products::whereRaw("status = 'Yes'")->orderByRaw('sort_order')->get()->toArray();

$sxi_cat_name = []; $sxi_cat_count = [];
foreach ($sxi_cats as $c) { $sxi_cat_name[$c['id']] = $c['name']; $sxi_cat_count[$c['id']] = 0; }
foreach ($sxi_all as $p) { if (isset($sxi_cat_count[$p['category_id']])) { $sxi_cat_count[$p['category_id']]++; } }

$sxi_title = trim($cms_dp['banner_heading'] ?? '') !== '' ? $cms_dp['banner_heading'] : ($cms_dp['name'] ?? 'Products & Services');
$sxi_lead  = sxi_plain($cms_dp['full_contents'] ?? '', 210);
if ($sxi_lead === '') { $sxi_lead = 'Premium, made-to-order packaging for food, sweets, retail and brands. Explore every collection and find the box that carries your product best.'; }

/* hero collage: first three products */
$sxi_hero = array_slice($sxi_all, 0, 3);
@endphp

<main class="sxi" id="main">

	{{-- ============ HERO ============ --}}
	<section class="sxi-hero">
		<span class="sxi-float sxi-float--a" data-depth="26"></span>
		<span class="sxi-float sxi-float--b" data-depth="-18"></span>
		<span class="sxi-float sxi-float--c" data-depth="34"></span>
		<div class="sxi-wrap sxi-hero__grid">
			<div>
				<ul class="sxi-crumbs"><li><a href="{{ url('/') }}">Home</a></li><li>{{ $cms_dp['name'] }}</li></ul>
				<span class="sxi-eyebrow">Products &amp; Services</span>
				<h1 class="sxi-h1" data-split>{{ $sxi_title }}</h1>
				<p class="sxi-lead">{{ $sxi_lead }}</p>
				<ul class="sxi-stats">
					<li><b>{{ count($sxi_cats) }}</b> Collections</li>
					<li><b>{{ count($sxi_all) }}</b> Products</li>
					<li>Made to order</li>
				</ul>
			</div>
			<div class="sxi-collage" aria-hidden="true">
				@foreach($sxi_hero as $hi => $hp)
					@php $hs = sxi_img($hp['slug'], $hp['image'], 'products'); @endphp
					<div class="sxi-arch sxi-arch--{{ $hi + 1 }} sxi-c{{ $hi * 2 }}" data-depth="{{ [14, -22, 30][$hi] }}">
						@if($hs !== '')<img src="{{ $hs }}" alt="" loading="eager" onerror="this.nextElementSibling.classList.add('is-on');this.remove()">@endif
						<span class="sxi-ph {{ $hs === '' ? 'is-on' : '' }}">{!! sxi_icon() !!}</span>
					</div>
				@endforeach
				@if(count($sxi_hero) === 0)
					<div class="sxi-arch sxi-arch--solo sxi-c0"><span class="sxi-ph is-on">{!! sxi_icon() !!}</span></div>
				@endif
				<div class="sxi-badge"><i><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="m5 12 4.5 4.5L19 7"/></svg></i><span><b>Made to order</b>Your size, your brand</span></div>
			</div>
		</div>
	</section>

	{{-- ============ COLLECTIONS (categories) ============ --}}
	@if(count($sxi_cats) > 0)
	<section class="sxi-section">
		<div class="sxi-wrap">
			<div class="sxi-secthead">
				<div><span class="sxi-eyebrow">Browse by type</span><h2>Our Collections</h2></div>
				<p>Pick a collection to see every box, bag and packaging style inside it.</p>
			</div>
			<div class="sxi-grid sxi-grid--cards">
				@foreach($sxi_cats as $ci => $crow)
					@include('pages.inner._tile', ['row' => $crow, 'i' => $ci, 'kind' => 'category', 'count' => $sxi_cat_count[$crow['id']]])
				@endforeach
			</div>
		</div>
	</section>
	@endif

	{{-- ============ ALL PRODUCTS (pinterest grid + filter) ============ --}}
	<section class="sxi-section sxi-section--white" id="all-products">
		<div class="sxi-wrap">
			<div class="sxi-secthead">
				<div><span class="sxi-eyebrow">The full range</span><h2>All Products</h2></div>
				<p>Showing <strong data-filter-count>{{ count($sxi_all) }}</strong> products. Tap a style to filter.</p>
			</div>

			@if(count($sxi_all) > 0)
			<div class="sxi-filter" data-filter-bar>
				<div class="sxi-filter__in" role="tablist" aria-label="Filter products">
					<button type="button" class="sxi-chip is-active" data-filter="all">All</button>
					@foreach($sxi_cats as $c)
						@if($sxi_cat_count[$c['id']] > 0)
							<button type="button" class="sxi-chip" data-filter="{{ $c['id'] }}">{{ $c['name'] }}<sup>{{ $sxi_cat_count[$c['id']] }}</sup></button>
						@endif
					@endforeach
				</div>
			</div>

			<div class="sxi-grid" data-filter-grid data-feature>
				@foreach($sxi_all as $pi => $prow)
					@include('pages.inner._tile', ['row' => $prow, 'i' => $pi, 'kind' => 'product', 'tag' => ($sxi_cat_name[$prow['category_id']] ?? ''), 'cat' => $prow['category_id'], 'feature' => $pi === 0])
				@endforeach
			</div>
			<div class="sxi-empty" data-filter-empty>
				<svg class="sxi-empty__ico" viewBox="0 0 64 64" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"><path d="M32 6 56 18v28L32 58 8 46V18z"/><path d="M8 18l24 12 24-12M32 30v28"/></svg>
				<p>No products in this collection yet.</p>
			</div>
			@else
			<div class="sxi-empty sxi-empty--static">
				<svg class="sxi-empty__ico" viewBox="0 0 64 64" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"><path d="M32 6 56 18v28L32 58 8 46V18z"/><path d="M8 18l24 12 24-12M32 30v28"/></svg>
				<p>Products are coming soon.</p>
			</div>
			@endif
		</div>
	</section>

	{{-- ============ CTA ============ --}}
	<section class="sxi-section">
		<div class="sxi-wrap">
			<div class="sxi-cta sxi-reveal">
				<div>
					<h2>Don't see your exact size or style?</h2>
					<p>Tell us what you are packing. We will design, print and deliver a box that fits it perfectly.</p>
				</div>
				<div class="sxi-cta__btns">
					<a class="sxi-btn sxi-btn--solid" href="{{ url('/') }}/get-quote.html">Get a Quote <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg></a>
					<a class="sxi-btn sxi-btn--ghost" href="{{ url('/') }}/contact-us.html">Contact us</a>
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
