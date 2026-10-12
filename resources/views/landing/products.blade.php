{{-- ======================= PRODUCTS =======================
     Needs: $db_category (featured categories), $data_services (heading source), $sx_link
     Content: Admin > Category (mark as featured). Wording: config/landing.php (products) --}}
@php
$sx_ph = count($data_services) > 0 ? $data_services[0] : null;
$p_eyebrow = $sx_ph ? $sx_ph['name'] : config('landing.products.eyebrow');
$p_title   = ($sx_ph && $sx_ph['tag_line'] != '') ? $sx_ph['tag_line'] : config('landing.products.title');
$p_lead    = ($sx_ph && !empty($sx_ph['short_contents'])) ? $sx_ph['short_contents'] : '';
@endphp
<section class="sx-section sx-section--white" id="products">
	<div class="sx-container">
		<x-site.section-head :eyebrow="(string) $p_eyebrow" :title="(string) $p_title" :lead="(string) $p_lead" />

		@if (count($db_category) > 0)
		<div class="sx-products">
			@foreach ($db_category as $row)
			@php $p_desc = isset($row['detail']) ? trim(strip_tags((string) $row['detail'])) : ''; @endphp
			<article class="sx-card sx-product sx-reveal">
				<div class="sx-product__img">
					@if (!empty($row['image']))
					<img src="{{ url('/public/upload/category/' . $row['image']) }}" alt="{{ $row['name'] }}" loading="lazy">
					@else
					<div class="sx-ph"><x-site.icon name="cube" /></div>
					@endif
				</div>
				<div class="sx-product__body">
					<h3><a href="{{ url('/' . $row['slug'] . '.html') }}">{{ $row['name'] }}</a></h3>
					@if ($p_desc != '')<p class="sx-clamp-3">{{ $p_desc }}</p>@endif
					<span class="sx-link">{{ config('landing.products.explore') }} <x-site.icon name="arrow" /></span>
				</div>
			</article>
			@endforeach
		</div>
		@else
		<div class="sx-card sx-empty sx-reveal"><div class="sx-empty__icon"><x-site.icon name="cube" /></div><div><h3>{{ config('landing.products.empty_title') }}</h3><p>{{ config('landing.products.empty_text') }}</p></div></div>
		@endif

		<div class="sx-center"><a href="{{ $sx_link(config('landing.products.view_all_link')) }}" class="sx-btn sx-btn--ghost">{{ config('landing.products.view_all') }} <x-site.icon name="arrow" /></a></div>
	</div>
</section>
