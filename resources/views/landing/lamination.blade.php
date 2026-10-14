{{-- ======================= LAMINATION =======================
     A slow, endless rail of photo tiles (tall + short mix). Hover: photo zooms, a gloss sheen sweeps across,
     the description slides up and an arrow button appears. Hover or Tab onto the rail to pause it.

     Needs : $data_lamination  (child pages of the CMS page set in config/landing.php > lamination > parent_id)
     Tile  : heading = title, tag_line = short description, banner = photo (public/upload/cms/), slug = link
     Text  : config/landing.php > lamination   (eyebrow, title, lead, parent_id)
     Look  : public/assets/main/css/sasa-lamination.css (linked in layouts/app.blade.php)

     3 or more child pages -> the moving rail.   Fewer (or parent_id not set) -> the section is simply not shown. --}}
@php
$lam_items = (!empty($data_lamination) && count($data_lamination) > 0) ? array_values((array) $data_lamination) : [];
$lam_rail  = count($lam_items) >= 3;

if ($lam_rail) {
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
<section class="lam" id="lamination"@if (!empty(config('landing.lamination.title'))) aria-labelledby="lam-title"@endif>
	@if (!empty(config('landing.lamination.title')))
	<div class="sx-container">
		<header class="lam__head sx-reveal">
			@if (!empty(config('landing.lamination.eyebrow')))<span class="sx-eyebrow">{{ config('landing.lamination.eyebrow') }}</span>@endif
			<h2 class="sx-title" id="lam-title">{!! config('landing.lamination.title') !!}</h2>
			@if (!empty(config('landing.lamination.lead')))<div class="sx-lead">{!! config('landing.lamination.lead') !!}</div>@endif
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
						$lt = (string) (isset($item['heading']) ? $item['heading'] : '');
						$ld = (string) (isset($item['tag_line']) ? $item['tag_line'] : '');
						$li_img = (string) (isset($item['banner']) ? $item['banner'] : '');
						$lh = !empty($item['slug']) ? url('/' . $item['slug'] . '.html') : '#';
					@endphp
					<div class="lam__tile lam__tile--{{ ($ci + $loop->index) % 3 }}{{ $li_img === '' ? ' lam__tile--noimg' : '' }}">
						<a href="{{ $lh }}" class="lam__link" aria-label="{{ strip_tags($lt) }}">
							@if ($li_img !== '')<img class="lam__img" src="{{ url('/public/upload/cms/' . $li_img) }}" alt="" loading="lazy" draggable="false">@endif
							<span class="lam__sheen" aria-hidden="true"></span>
							<span class="lam__cap">
								<span class="lam__name">{!! strip_tags($lt) !!}</span>
								@if ($ld !== '')<span class="lam__tag">{{ strip_tags($ld) }}</span>@endif
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
</section>

<script>
/* Lamination rail: repeats one set until it fills the screen twice, then drifts by exactly one set (endless loop). */
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
