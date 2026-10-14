{{-- ======================= PRODUCTS (endless rail) -> save as resources/views/landing/products.blade.php =======================
     Uses the existing lamination rail look (public/assets/main/css/sasa-lamination.css) - no new CSS file needed,
     only the small additions in css-additions-lamination.css (View all button + icon tile).

     Needs : $data_products  (rows from tbl_products: name, slug, image, short_contents, FullContents) - queried in landing/index.blade.php
     Photos: public/assets/main/images/ProductImages/<tbl_products.image>   (folder: config landing.products.image_dir)
             Missing file = soft palette tile with an icon. Just drop the photos in later.
     Text  : config/landing.php > products   (eyebrow, title, lead, view_all, view_all_link, image_dir, url_pattern)

     3 or more products -> the moving rail.  Fewer -> the section is simply not shown. --}}
@php
$lam_items = (!empty($data_products) && count($data_products) > 0) ? array_values((array) $data_products) : [];
$lam_rail  = count($lam_items) >= 3;

if ($lam_rail) {
	$lam_dir     = trim((string) config('landing.products.image_dir', 'assets/main/images/ProductImages'), '/');
	$lam_urlpat  = (string) config('landing.products.url_pattern', '/{slug}.html');   // change if your product pages use another address
	$lam_all     = (string) config('landing.products.view_all_link', '/products-and-services.html');

	// pattern = how many tiles stack in each column (2 = tall + short, 1 = one full-height tile)
	$lam_pattern = [2, 1, 2, 2, 1];
	$lam_widths  = [1, .85, 1.15];
	$lam_cols = [];
	$li = 0; $lp = 0;
	while ($li < count($lam_items)) {
		$take = $lam_pattern[$lp % count($lam_pattern)];
		$lam_cols[] = array_slice($lam_items, $li, $take);
		$li += $take; $lp++;
	}
}
@endphp

@if ($lam_rail)
<section class="lam" id="products"@if (!empty(config('landing.products.title'))) aria-labelledby="lam-title"@endif>
	@if (!empty(config('landing.products.title')))
	<div class="sx-container">
		<header class="lam__head sx-reveal">
			@if (!empty(config('landing.products.eyebrow')))<span class="sx-eyebrow">{{ config('landing.products.eyebrow') }}</span>@endif
			<h2 class="sx-title" id="lam-title">{!! config('landing.products.title') !!}</h2>
			@if (!empty(config('landing.products.lead')))<div class="sx-lead">{!! config('landing.products.lead') !!}</div>@endif
		</header>
	</div>
	@endif

	<div class="lam__rail" data-lam>
		<div class="lam__track">
			<div class="lam__set" data-lam-set>
				@foreach ($lam_cols as $ci => $col)
				<div class="lam__col lam__col--{{ $ci % 2 ? 'b' : 'a' }}" style="--cw: {{ $lam_widths[$ci % count($lam_widths)] }}; --d: {{ $ci * 90 }}ms">
					@foreach ($col as $item)
					@php
						$lt = trim(strip_tags((string) ($item['name'] ?? '')));
						$ld = trim(strip_tags((string) ($item['short_contents'] ?? '')));
						if ($ld === '') { $ld = trim(strip_tags(html_entity_decode((string) ($item['FullContents'] ?? '')))); }
						$ld = mb_strimwidth(preg_replace('/\s+/', ' ', $ld), 0, 110, '…');

						// use the photo only if the file really exists, so there is never a broken picture
						$pf = trim((string) ($item['image'] ?? ''));
						$li_img = ($pf !== '' && file_exists(public_path($lam_dir . '/' . $pf)))
							? url('/public/' . $lam_dir . '/' . rawurlencode($pf)) : '';

						$lh = !empty($item['slug']) ? url(str_replace('{slug}', $item['slug'], $lam_urlpat)) : '#';
					@endphp
					<div class="lam__tile lam__tile--{{ ($ci + $loop->index) % 3 }}{{ $li_img === '' ? ' lam__tile--noimg' : '' }}">
						<a href="{{ $lh }}" class="lam__link" aria-label="{{ $lt }}">
							@if ($li_img !== '')
								<img class="lam__img" src="{{ $li_img }}" alt="" loading="lazy" draggable="false">
							@else
								<span class="lam__ph" aria-hidden="true"><x-site.icon name="cube" /></span>
							@endif
							<span class="lam__sheen" aria-hidden="true"></span>
							<span class="lam__cap">
								<span class="lam__name">{{ $lt }}</span>
								@if ($ld !== '')<span class="lam__tag">{{ $ld }}</span>@endif
							</span>
							<span class="lam__go" aria-hidden="true"><x-site.icon name="arrow" /></span>
						</a>
					</div>
					@endforeach
				</div>
				@endforeach
			</div>
		</div>
	</div>

	<div class="sx-container">
		<a href="{{ url($lam_all) }}" class="lam__more">
			<span>{{ config('landing.products.view_all', 'View all products') }}</span>
			<i aria-hidden="true"><x-site.icon name="arrow" /></i>
		</a>
	</div>
</section>

<script>
/* Rail: repeats one set until it fills the screen twice, then drifts by exactly one set (endless loop). */
(function () {
	var rail = document.querySelector('[data-lam]');
	if (!rail) { return; }
	var track = rail.querySelector('.lam__track');
	var set   = rail.querySelector('[data-lam-set]');
	var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

	function build() {
		[].forEach.call(track.querySelectorAll('[data-clone]'), function (n) { n.parentNode.removeChild(n); });
		var w = set.getBoundingClientRect().width;
		if (!w) { return; }
		var copies = Math.max(1, Math.ceil((rail.clientWidth * 2) / w));
		for (var i = 0; i < copies; i++) {
			var c = set.cloneNode(true);
			c.removeAttribute('data-lam-set');
			c.setAttribute('data-clone', '');
			c.setAttribute('aria-hidden', 'true');
			[].forEach.call(c.querySelectorAll('a'), function (a) { a.tabIndex = -1; });
			track.appendChild(c);
		}
		rail.style.setProperty('--lam-w', w + 'px');
		rail.style.setProperty('--lam-dur', Math.max(40, w / 40) + 's');   // about 40px per second
		if (!reduce) { rail.classList.add('is-live'); }
	}
	build();
	var t; window.addEventListener('resize', function () { clearTimeout(t); t = setTimeout(build, 250); });

	if (!('IntersectionObserver' in window)) { rail.classList.add('is-in'); return; }
	var io = new IntersectionObserver(function (e) {
		if (e[0].isIntersecting) { rail.classList.add('is-in'); io.disconnect(); }
	}, { threshold: 0.12 });
	io.observe(rail);
})();
</script>
@endif