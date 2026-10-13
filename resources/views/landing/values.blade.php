{{-- ======================= MISSION & VALUES (expanding panels) =======================
     Needs: $features_dp   Content: Admin > Services   Heading: config/landing.php (values)
     Look: public/assets/main/css/sasa-values.css   Behaviour: public/assets/main/js/sasa-values.js
     Panels open on hover (desktop), tap (phone) or keyboard; one opens by itself every few seconds. --}}
@if (count($features_dp) > 0)
<section class="sx-section sx-vs" id="mission" data-sx-values data-interval="5500">
	<div class="sx-container">
		<x-site.section-head :eyebrow="config('landing.values.eyebrow')" :title="config('landing.values.title')" :lead="config('landing.values.lead')" />

		<div class="sx-vl" role="list">
			@foreach ($features_dp as $k => $row)
			@php
				$tone = ['sage', 'cream', 'deep', 'blush'][$k % 4];
				$txt  = trim(preg_replace('/\s+/', ' ', strip_tags(html_entity_decode((string) ($row['FullContents'] ?? '')))));
				$txt  = mb_strimwidth($txt, 0, 360, '…');
			@endphp
			<article class="sx-vp sx-vp--{{ $tone }}{{ $k === 0 ? ' is-open' : '' }}" style="--i: {{ $k }}" role="listitem" tabindex="0" aria-expanded="{{ $k === 0 ? 'true' : 'false' }}">
				<div class="sx-vp__top">
					<span class="sx-vp__num">{{ str_pad($k + 1, 2, '0', STR_PAD_LEFT) }}</span>
					<span class="sx-vp__icon">
						@if (!empty($row['icon_class']))
						<img src="{{ url('/public/upload/services/' . $row['icon_class']) }}" alt="" loading="lazy" onerror="this.remove()">
						@else
						<x-site.icon name="check" />
						@endif
					</span>
				</div>
				<h3 class="sx-vp__vtitle" aria-hidden="true">{{ $row['name'] }}</h3>
				<div class="sx-vp__content">
					<h3 class="sx-vp__title">{{ $row['name'] }}</h3>
					<div class="sx-vp__txt"><p>{{ $txt }}</p></div>
				</div>
				<span class="sx-vp__progress" aria-hidden="true"></span>
			</article>
			@endforeach
		</div>
	</div>
</section>
@endif