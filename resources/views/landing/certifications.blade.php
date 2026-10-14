{{-- ======================= CERTIFICATIONS (shield seals + confetti) =======================
     Save as resources/views/landing/certifications.blade.php
     Wording + the list: config/landing.php > certifications  (eyebrow, title, lead, hint, image_dir, items)
     Each item: name (caption), short (text inside the shield), image (optional logo file), note (optional small line)
     Logo files go in public/upload/certifications/  (folder = config image_dir). No image = shield with the short name.
     Open-ended: add as many items as you like. Look: public/assets/main/css/sasa-trust.css --}}
@php
$ct_dir   = trim((string) config('landing.certifications.image_dir', 'upload/certifications'), '/');
$ct_items = (array) config('landing.certifications.items', []);
if (count($ct_items) === 0) {
	$ct_items = [
		['name' => 'ISO 9001 Certified', 'short' => 'ISO 9001', 'image' => '', 'note' => ''],
		['name' => 'FSSC Certified',     'short' => 'FSSC',     'image' => '', 'note' => ''],
	];
}
@endphp
<section class="ct sxt" id="certifications" aria-labelledby="ct-title">
	{{-- shared gradient for every shield --}}
	<svg width="0" height="0" style="position:absolute" aria-hidden="true" focusable="false">
		<defs>
			<linearGradient id="ctGrad" x1="0" y1="0" x2="0" y2="1">
				<stop offset="0" stop-color="#ffffff"/><stop offset=".55" stop-color="#eef4ec"/><stop offset="1" stop-color="#B8CFB3"/>
			</linearGradient>
		</defs>
	</svg>
	<span class="ct__blob ct__blob--a" aria-hidden="true"></span>
	<span class="ct__blob ct__blob--b" aria-hidden="true"></span>

	<div class="sx-container">
		<header class="sxt-head">
			@if (config('landing.certifications.eyebrow'))<span class="sxt-eyebrow">{{ config('landing.certifications.eyebrow', 'Quality & trust') }}</span>@endif
			<h2 class="sxt-title" id="ct-title">{!! config('landing.certifications.title', 'Certified for confidence') !!}</h2>
			@if (config('landing.certifications.lead'))<p class="sxt-lead">{!! config('landing.certifications.lead') !!}</p>@endif
		</header>

		<div class="ct__stage" data-ct>
			@foreach ($ct_items as $i => $c)
			@php
				$c_name  = trim(strip_tags((string) ($c['name'] ?? '')));
				$c_short = trim(strip_tags((string) ($c['short'] ?? $c_name)));
				$c_note  = trim(strip_tags((string) ($c['note'] ?? '')));
				$c_file  = trim((string) ($c['image'] ?? ''));
				$c_img   = ($c_file !== '' && file_exists(public_path($ct_dir . '/' . $c_file))) ? url('/public/' . $ct_dir . '/' . rawurlencode($c_file)) : '';
			@endphp
			<div class="ct__item" style="--i: {{ $i }}" tabindex="0" role="button" aria-label="{{ $c_name }}">
				<div class="ct__shield">
					<span class="ct__halo" aria-hidden="true"></span>
					<svg class="ct__svg" viewBox="0 0 200 240" aria-hidden="true">
						<path class="ct__outline" d="M100 8 L184 38 V112 C184 168 148 208 100 230 C52 208 16 168 16 112 V38 Z"/>
						<path class="ct__inline" d="M100 22 L172 47 V112 C172 160 142 195 100 215 C58 195 28 160 28 112 V47 Z"/>
					</svg>
					<div class="ct__inner">
						@if ($c_img !== '')
							<img class="ct__logo" src="{{ $c_img }}" alt="" loading="lazy" draggable="false">
						@else
							<span class="ct__short">{{ $c_short }}</span>
						@endif
					</div>
					<span class="ct__tick" aria-hidden="true">
						<svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.5l4.5 4.5L19 7.5"/></svg>
					</span>
				</div>
				<h3 class="ct__name">{{ $c_name }}</h3>
				@if ($c_note !== '')<p class="ct__note">{{ $c_note }}</p>@endif
			</div>
			@endforeach
		</div>

		@if (config('landing.certifications.hint', 'Tap a seal to celebrate'))
		<p class="ct__hint">{{ config('landing.certifications.hint', 'Tap a seal to celebrate') }}</p>
		@endif
	</div>
</section>

<script>
/* Certifications: seals draw themselves when scrolled into view, then confetti pops (again on tap / hover). */
(function () {
	var stage = document.querySelector('[data-ct]');
	if (!stage) { return; }
	var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
	var items = [].slice.call(stage.querySelectorAll('.ct__item'));

	/* ----- tiny canvas confetti (no library) ----- */
	var canvas, ctx, parts = [], running = false, dpr = Math.min(window.devicePixelRatio || 1, 2);
	var colors = ['#B8CFB3', '#84A282', '#F6E2E9', '#e6b3c4', '#b79f6b', '#ffffff', '#4d6b4f'];
	function setup() {
		if (canvas) { return; }
		canvas = document.createElement('canvas');
		canvas.setAttribute('aria-hidden', 'true');
		canvas.style.cssText = 'position:fixed;left:0;top:0;width:100%;height:100%;pointer-events:none;z-index:9999';
		document.body.appendChild(canvas);
		ctx = canvas.getContext('2d');
		size(); window.addEventListener('resize', size);
	}
	function size() { canvas.width = window.innerWidth * dpr; canvas.height = window.innerHeight * dpr; ctx.setTransform(dpr, 0, 0, dpr, 0, 0); }
	function burst(x, y, n) {
		if (reduce) { return; }
		setup();
		for (var i = 0; i < n; i++) {
			var a = -Math.PI / 2 + (Math.random() - .5) * Math.PI * 1.15, sp = 6 + Math.random() * 10;
			parts.push({ x: x, y: y, vx: Math.cos(a) * sp, vy: Math.sin(a) * sp, w: 6 + Math.random() * 7, h: 4 + Math.random() * 6,
				r: Math.random() * 6.28, vr: (Math.random() - .5) * .4, c: colors[(Math.random() * colors.length) | 0],
				life: 0, max: 100 + Math.random() * 70, round: Math.random() < .3 });
		}
		if (!running) { running = true; requestAnimationFrame(tick); }
	}
	function tick() {
		ctx.clearRect(0, 0, window.innerWidth, window.innerHeight);
		parts = parts.filter(function (p) { return p.life < p.max && p.y < window.innerHeight + 40; });
		parts.forEach(function (p) {
			p.life++; p.vx *= .985; p.vy = p.vy * .985 + .24; p.x += p.vx; p.y += p.vy; p.r += p.vr;
			ctx.save(); ctx.globalAlpha = Math.min(1, (p.max - p.life) / 28);
			ctx.translate(p.x, p.y); ctx.rotate(p.r); ctx.fillStyle = p.c;
			if (p.round) { ctx.beginPath(); ctx.arc(0, 0, p.w / 2.4, 0, 6.28); ctx.fill(); } else { ctx.fillRect(-p.w / 2, -p.h / 2, p.w, p.h); }
			ctx.restore();
		});
		if (parts.length) { requestAnimationFrame(tick); } else { running = false; ctx.clearRect(0, 0, window.innerWidth, window.innerHeight); }
	}
	function popFrom(el, n) {
		var r = el.querySelector('.ct__shield').getBoundingClientRect();
		burst(r.left + r.width / 2, r.top + r.height * .35, n);
	}

	/* ----- reveal + first celebration ----- */
	function reveal() {
		items.forEach(function (it) { it.classList.add('is-in'); });
		items.forEach(function (it, i) { setTimeout(function () { popFrom(it, 70); }, 1100 + i * 380); });
	}
	if ('IntersectionObserver' in window) {
		var io = new IntersectionObserver(function (e) { if (e[0].isIntersecting) { reveal(); io.disconnect(); } }, { threshold: 0.45 });
		io.observe(stage);
	} else { reveal(); }

	/* ----- celebrate again ----- */
	items.forEach(function (it) {
		var last = 0;
		function again() { var now = Date.now(); if (now - last < 1400) { return; } last = now; popFrom(it, 55); }
		it.addEventListener('click', again);
		it.addEventListener('mouseenter', again);
		it.addEventListener('keydown', function (e) { if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); again(); } });
	});
})();
</script>