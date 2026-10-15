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
$sxi_p   = App\Model\Products::find($cms_dp['id']);
$sxi_p   = $sxi_p ? $sxi_p->toArray() : ['slug' => '', 'image' => ''];
$sxi_cat = App\Model\Category::find($cms_dp['category_id']);
$sxi_cat = $sxi_cat ? $sxi_cat->toArray() : null;

/* gallery = ProductImages/{slug}.ext  +  ProductImages/{slug}-2.ext ...  +  Admin uploads (main image + extra images) */
$sxi_gal = [];
$main = sxi_img($sxi_p['slug'] ?? '', $sxi_p['image'] ?? '', 'products');
if ($main !== '') { $sxi_gal[] = $main; }
foreach (sxi_extra_imgs($sxi_p['slug'] ?? '') as $u) { $sxi_gal[] = $u; }
foreach (App\Model\Projectimages::whereRaw('project_id = ?', array($cms_dp['id']))->get()->toArray() as $r) {
	if (!empty($r['image']) && is_file(base_path('public/upload/products/' . $r['image']))) { $sxi_gal[] = url('/public/upload/products/' . rawurlencode($r['image'])); }
}
$sxi_gal = array_values(array_unique($sxi_gal));

$sxi_related = [];
if ($sxi_cat) {
	$sxi_related = App\Model\Products::whereRaw("status = 'Yes' AND category_id = " . (int) $cms_dp['category_id'] . " AND id != " . (int) $cms_dp['id'])->orderByRaw('sort_order')->limit(8)->get()->toArray();
}
$sxi_has_text = trim(strip_tags(html_entity_decode((string) $cms_dp['full_contents']))) !== '';
@endphp

<main class="sxi" id="main">
	<section class="sxi-hero" style="padding-bottom:clamp(32px,4vw,56px)">
		<span class="sxi-float sxi-float--a" data-depth="26"></span>
		<span class="sxi-float sxi-float--b" data-depth="-18"></span>
		<div class="sxi-wrap">
			<ul class="sxi-crumbs" style="margin-bottom:30px">
				<li><a href="{{ url('/') }}">Home</a></li>
				<li><a href="{{ url('/products-and-services.html') }}">Products &amp; Services</a></li>
				@if($sxi_cat)<li><a href="{{ url('/' . $sxi_cat['slug'] . '.html') }}">{{ $sxi_cat['name'] }}</a></li>@endif
				<li>{{ $cms_dp['name'] }}</li>
			</ul>

			<div class="sxi-pd">
				{{-- gallery --}}
				<div class="sxi-gal">
					<div class="sxi-main {{ count($sxi_gal) ? '' : 'is-empty' }} sxi-c0" data-gal-main>
						@if(count($sxi_gal))<img src="{{ $sxi_gal[0] }}" alt="{{ $cms_dp['name'] }}">@endif
						<span class="sxi-ph">{!! sxi_icon() !!}</span>
						@if(count($sxi_gal))<span class="sxi-zoomhint">Hover to zoom · click to enlarge</span>@endif
					</div>
					@if(count($sxi_gal) > 1)
					<div class="sxi-thumbs">
						@foreach($sxi_gal as $gi => $gu)
							<button type="button" class="sxi-thumb {{ $gi === 0 ? 'is-active' : '' }}" data-gal-thumb data-src="{{ $gu }}" aria-label="Photo {{ $gi + 1 }}"><img src="{{ $gu }}" alt="" loading="lazy"></button>
						@endforeach
					</div>
					@endif
				</div>

				{{-- info --}}
				<div class="sxi-info">
					<div class="sxi-tagrow">
						@if($sxi_cat)<a class="sxi-tag" href="{{ url('/' . $sxi_cat['slug'] . '.html') }}">{{ $sxi_cat['name'] }}</a>@endif
						@if(!empty($cms_dp['product_code']))<span class="sxi-tag sxi-tag--blush">Code {{ $cms_dp['product_code'] }}</span>@endif
					</div>
					<h1 data-split>{{ $cms_dp['name'] }}</h1>
					@if(trim($cms_dp['heading']) !== '' && $cms_dp['heading'] !== $cms_dp['name'])<p class="sxi-info__sub">{{ $cms_dp['heading'] }}</p>@endif

					@if($sxi_has_text)
						<div class="sxi-prose">{!! $cms_dp['full_contents'] !!}</div>
					@else
						<div class="sxi-prose"><p>Custom-made to your size, finish and branding. Send us your requirements and we will prepare a quote.</p></div>
					@endif

					<div class="sxi-perks">
						<div class="sxi-perk"><i><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 17 17 3l4 4L7 21H3z"/></svg></i>Custom sizes</div>
						<div class="sxi-perk"><i><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3a9 9 0 1 0 0 18c1.7 0 2-1 1.5-2-.6-1.2.1-2.5 1.5-2.5H17a4 4 0 0 0 4-4C21 6.7 17 3 12 3z"/></svg></i>Branded printing</div>
						<div class="sxi-perk"><i><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 7l9-4 9 4-9 4zM3 12l9 4 9-4M3 17l9 4 9-4"/></svg></i>Bulk orders</div>
					</div>

					<div class="sxi-actions">
						<a class="sxi-btn sxi-btn--dark" href="{{ url('/') }}/get-quote.html">Get a Quote <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg></a>
						<a class="sxi-btn sxi-btn--line" target="_blank" rel="noopener" href="https://wa.me/+923202956541?text={{ rawurlencode('Hi, I am interested in ' . $cms_dp['name']) }}">Ask on WhatsApp</a>
					</div>
				</div>
			</div>
		</div>
	</section>

	@if(count($sxi_related) > 0)
	<section class="sxi-section sxi-section--white">
		<div class="sxi-wrap">
			<div class="sxi-secthead">
				<div><span class="sxi-eyebrow">You may also like</span><h2>More {{ $sxi_cat['name'] }}</h2></div>
				<a class="sxi-btn sxi-btn--line" href="{{ url('/' . $sxi_cat['slug'] . '.html') }}">View all</a>
			</div>
			<div class="sxi-grid">
				@foreach($sxi_related as $ri => $rrow)
					@include('pages.inner._tile', ['row' => $rrow, 'i' => $ri, 'kind' => 'product', 'tag' => $sxi_cat['name']])
				@endforeach
			</div>
		</div>
	</section>
	@endif

	<section class="sxi-section">
		<div class="sxi-wrap">
			<div class="sxi-cta sxi-reveal">
				<div><h2>Ready to order {{ $cms_dp['name'] }}?</h2><p>Send us your size and quantity. We will reply with a quote.</p></div>
				<div class="sxi-cta__btns">
					<a class="sxi-btn sxi-btn--solid" href="{{ url('/') }}/get-quote.html">Get a Quote <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg></a>
					<a class="sxi-btn sxi-btn--ghost" href="{{ url('/') }}/contact-us.html">Contact us</a>
				</div>
			</div>
		</div>
	</section>

	{{-- lightbox --}}
	<div class="sxi-lb" data-lb role="dialog" aria-label="Image viewer" aria-modal="true">
		<button type="button" class="sxi-lb__x" data-lb-x aria-label="Close">&times;</button>
		<button type="button" class="sxi-lb__p" data-lb-p aria-label="Previous">&#8249;</button>
		<img src="" alt="">
		<button type="button" class="sxi-lb__n" data-lb-n aria-label="Next">&#8250;</button>
	</div>
</main>
@stop

@section('footer')
@include('partial.footer')
@stop

@section('customscript')
<script src="{{ url('/') }}/public/assets/main/js/sasa-inner.js"></script>
@stop
