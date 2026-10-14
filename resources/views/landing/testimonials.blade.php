{{-- ======================= RATINGS & TESTIMONIALS + PARTNERS =======================
     Save as resources/views/landing/testimonials.blade.php  (index already includes it - nothing else to include)
     Needs: $members_dp (Admin > Members = partner logos, files in public/upload/members/)
     Ratings : config/landing.php > testimonials  (rating, rating_label, items = real reviews, placeholder_count)
     Partners: config/landing.php > testimonials  (partners_eyebrow, partners_heading, partners_lead)
     Look: public/assets/main/css/sasa-trust.css --}}
@php
$rv_items = (array) config('landing.testimonials.items', []);
$rv_rating = (float) config('landing.testimonials.rating', 5);
$rv_ph    = (int) config('landing.testimonials.placeholder_count', 3);

/* five stars; each fills from empty to gold (supports 4.5 etc.) */
$rv_stars = function ($value, $cls = '') {
	$path = 'M12 2.5l2.94 5.96 6.58.96-4.76 4.64 1.12 6.55L12 17.52l-5.88 3.09 1.12-6.55L2.48 9.42l6.58-.96z';
	$out = '<span class="rv-stars ' . $cls . '" role="img" aria-label="' . e(rtrim(rtrim(number_format($value, 1), '0'), '.')) . ' out of 5 stars">';
	for ($s = 1; $s <= 5; $s++) {
		$f = max(0, min(1, $value - ($s - 1)));
		$out .= '<span class="rv-star" style="--k:' . ($s - 1) . ';--f:' . $f . '" aria-hidden="true">'
			. '<svg class="rv-star__base" viewBox="0 0 24 24"><path d="' . $path . '"/></svg>'
			. '<svg class="rv-star__fill" viewBox="0 0 24 24"><path d="' . $path . '"/></svg></span>';
	}
	return $out . '</span>';
};
@endphp

<section class="rv sxt" id="ratings" aria-labelledby="rv-title" data-reveal>
	<div class="sx-container">
		<header class="sxt-head">
			<span class="sxt-eyebrow">{{ config('landing.testimonials.eyebrow', 'Customer feedback') }}</span>
			<h2 class="sxt-title" id="rv-title">{!! config('landing.testimonials.title', 'Ratings &amp; Testimonials') !!}</h2>
		</header>

		<div class="rv__score">
			<div class="rv__num">{{ number_format($rv_rating, 1) }}</div>
			<div>
				{!! $rv_stars($rv_rating, 'rv-stars--lg') !!}
				<p>{{ config('landing.testimonials.rating_label', 'Overall customer rating') }}</p>
			</div>
		</div>

		<div class="rv__list">
			@if (count($rv_items) > 0)
				@foreach ($rv_items as $k => $rv)
				<figure class="rv__card" style="--k: {{ $k }}">
					<span class="rv__quote" aria-hidden="true"><x-site.icon name="quote" /></span>
					{!! $rv_stars((float) ($rv['rating'] ?? 5)) !!}
					<blockquote>&ldquo;{{ $rv['quote'] ?? '' }}&rdquo;</blockquote>
					<figcaption>
						<span class="rv__av" aria-hidden="true">{{ mb_strtoupper(mb_substr(trim((string) ($rv['name'] ?? '?')), 0, 1)) }}</span>
						<cite>{{ $rv['name'] ?? '' }}@if (!empty($rv['role']))<small>{{ $rv['role'] }}</small>@endif</cite>
					</figcaption>
				</figure>
				@endforeach
			@else
				@for ($k = 0; $k < $rv_ph; $k++)
				<figure class="rv__card rv__card--empty" style="--k: {{ $k }}">
					<span class="rv__quote" aria-hidden="true"><x-site.icon name="quote" /></span>
					{!! $rv_stars(5) !!}
					<div class="rv__lines" aria-hidden="true"><i></i><i></i><i></i></div>
					<figcaption>
						<span class="rv__av" aria-hidden="true">&#9733;</span>
						<cite>{{ config('landing.testimonials.placeholder_name', 'Your customer\'s review') }}<small>{{ config('landing.testimonials.placeholder_text', 'Genuine feedback will appear here') }}</small></cite>
					</figcaption>
				</figure>
				@endfor
			@endif
		</div>
	</div>
</section>

{{-- ---------------------- PARTNERS ---------------------- --}}
@php
$pt_logos = [];
foreach ((array) ($members_dp ?? []) as $pt_r) {
	if (!empty($pt_r['image'])) { $pt_logos[] = $pt_r; }
}
$pt_rows = count($pt_logos) >= 4 ? [$pt_logos, array_reverse($pt_logos)] : [$pt_logos];
@endphp
<section class="pt sxt" id="partners" aria-labelledby="pt-title" data-reveal>
	<div class="sx-container">
		<header class="sxt-head">
			<span class="sxt-eyebrow">{{ config('landing.testimonials.partners_eyebrow', 'Our Partners') }}</span>
			<h2 class="sxt-title" id="pt-title">{!! config('landing.testimonials.partners_heading', 'Brands that grow with us') !!}</h2>
			<p class="sxt-lead">{!! config('landing.testimonials.partners_lead', 'We are proud to be the packaging partner behind businesses that care about quality.') !!}</p>
		</header>
	</div>

	@if (count($pt_logos) > 0)
		@foreach ($pt_rows as $ri => $row_logos)
		<div class="pt__rail{{ $ri === 1 ? ' pt__rail--rev' : '' }}" data-pt>
			<div class="pt__track">
				<ul class="pt__set" data-pt-set>
					@foreach ($row_logos as $row)
					<li class="pt__tile">
						@php $pt_img = url('/public/upload/members/' . $row['image']); @endphp
						@if (!empty($row['slug']))
						<a href="{{ $row['slug'] }}" target="_blank" rel="noopener" aria-label="{{ $row['name'] }}"><img src="{{ $pt_img }}" alt="{{ $row['name'] }}" loading="lazy" draggable="false"></a>
						@else
						<span><img src="{{ $pt_img }}" alt="{{ $row['name'] }}" loading="lazy" draggable="false"></span>
						@endif
						<i class="pt__sheen" aria-hidden="true"></i>
					</li>
					@endforeach
				</ul>
			</div>
		</div>
		@endforeach
	@else
		{{-- no logos yet: shimmering slots so the section already looks designed --}}
		<div class="pt__rail pt__rail--static">
			<ul class="pt__set pt__set--ph">
				@for ($p = 0; $p < 6; $p++)
				<li class="pt__tile pt__tile--ph" style="--k: {{ $p }}"><x-site.icon name="badge" /><span>{{ config('landing.testimonials.partner_placeholder', 'Partner logo') }}</span></li>
				@endfor
			</ul>
		</div>
	@endif
</section>

<script>
(function () {
	/* scroll reveal for stars + section entrance */
	var secs = document.querySelectorAll('[data-reveal]');
	if ('IntersectionObserver' in window) {
		var io = new IntersectionObserver(function (en) {
			en.forEach(function (e) { if (e.isIntersecting) { e.target.classList.add('is-in'); io.unobserve(e.target); } });
		}, { threshold: 0.2 });
		[].forEach.call(secs, function (s) { s.classList.add('is-armed'); io.observe(s); });
	}

	/* partner logo rails: repeat one set until it fills the screen twice, then drift by exactly one set */
	var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
	function build(rail) {
		var track = rail.querySelector('.pt__track'), set = rail.querySelector('[data-pt-set]');
		[].forEach.call(track.querySelectorAll('[data-clone]'), function (n) { n.parentNode.removeChild(n); });
		var w = set.getBoundingClientRect().width;
		if (!w) { return; }
		var copies = Math.max(1, Math.ceil((rail.clientWidth * 2) / w));
		for (var i = 0; i < copies; i++) {
			var c = set.cloneNode(true);
			c.removeAttribute('data-pt-set'); c.setAttribute('data-clone', ''); c.setAttribute('aria-hidden', 'true');
			[].forEach.call(c.querySelectorAll('a'), function (a) { a.tabIndex = -1; });
			track.appendChild(c);
		}
		rail.style.setProperty('--pt-w', w + 'px');
		rail.style.setProperty('--pt-dur', Math.max(30, w / 45) + 's');
		if (!reduce) { rail.classList.add('is-live'); }
	}
	var rails = document.querySelectorAll('[data-pt]');
	[].forEach.call(rails, build);
	var t; window.addEventListener('resize', function () { clearTimeout(t); t = setTimeout(function () { [].forEach.call(rails, build); }, 250); });
})();
</script>