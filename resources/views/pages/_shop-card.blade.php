{{-- One tile of the Pinterest grid. Needs: $p (ShopProduct), $page (config shop.page)
     Big picture on top (shape comes from the picture itself), small panel below with name, text, price, Order Now. --}}
@php $second = ($p->image_urls[1] ?? null); @endphp
<article class="shp-card" data-shp-card>
	<a class="shp-card__media" href="{{ $p->url }}" aria-label="{{ $p->name }}"
	   style="aspect-ratio: {{ $p->cover_ratio ?: 0.85 }}">
		<img class="shp-card__img" src="{{ $p->image_url }}" alt="{{ $p->name }}" loading="lazy" decoding="async">
		@if($second)<img class="shp-card__img shp-card__img--alt" src="{{ $second }}" alt="" loading="lazy" decoding="async" aria-hidden="true">@endif
		@if($p->is_featured)<span class="shp-tag">Featured</span>@endif
	</a>
	<div class="shp-card__body">
		<h3 class="shp-card__title"><a href="{{ $p->url }}">{{ $p->name }}</a></h3>
		@if($p->summary)<p class="shp-card__text">{{ $p->summary }}</p>@endif
		<div class="shp-card__foot">
			<span class="shp-price {{ $p->has_price ? '' : 'is-ask' }}">{{ $p->price_label }}</span>
			<a class="shp-btn shp-btn--sm" href="{{ $p->order_url }}" target="_blank" rel="noopener">{{ $page['order_label'] }}</a>
		</div>
	</div>
</article>
