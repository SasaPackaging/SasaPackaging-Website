{{-- ======================= NEWS TICKER (between navbar and hero) =======================
     Save as resources/views/landing/ticker.blade.php, include it in landing/index.blade.php just BEFORE hero.
     Shows the latest news headlines ($sx_news). No news yet = the "no updates yet" line from config.
     Speed: config/landing.php > news > ticker_speed  (pixels per second, default 100; 70 = calm, 150 = fast) --}}
@php
$tk_items = [];
foreach ((array) ($sx_news ?? []) as $tk_p) {
	$tk_h = trim(strip_tags((string) ($tk_p['heading'] ?? '')));
	if ($tk_h !== '') { $tk_items[] = $tk_h; }
}
if (count($tk_items) === 0) {
	$tk_items[] = config('landing.news.ticker_empty', 'No updates yet — exhibitions, certifications and new openings will be announced here.');
}
@endphp
<div class="tk sxt" role="region" aria-label="{{ config('landing.news.ticker_label', 'News & Events') }}">
	<a class="tk__label" href="#news-events">
		<i class="tk__dot" aria-hidden="true"></i>
		<span>{{ config('landing.news.ticker_label', 'News & Events') }}</span>
	</a>
	<div class="tk__view" data-tk data-speed="{{ (int) config('landing.news.ticker_speed', 100) }}">
		<div class="tk__track">
			<div class="tk__set" data-tk-set>
				@foreach ($tk_items as $tk_t)
				<a class="tk__item" href="#news-events">{{ $tk_t }}</a><span class="tk__sep" aria-hidden="true">&#10022;</span>
				@endforeach
			</div>
		</div>
	</div>
</div>
<script>
/* Ticker: repeats one set until it fills the screen twice, then drifts by exactly one set (endless).
   Speed = pixels per second (data-speed), so one short line and many long lines move equally fast. */
(function () {
	var view = document.querySelector('[data-tk]');
	if (!view) { return; }
	var track = view.querySelector('.tk__track'), set = view.querySelector('[data-tk-set]');
	var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
	var speed = parseInt(view.getAttribute('data-speed'), 10) || 100;
	function build() {
		[].forEach.call(track.querySelectorAll('[data-clone]'), function (n) { n.parentNode.removeChild(n); });
		var w = set.getBoundingClientRect().width;
		if (!w) { return; }
		var copies = Math.max(1, Math.ceil((view.clientWidth * 2) / w));
		for (var i = 0; i < copies; i++) {
			var c = set.cloneNode(true);
			c.removeAttribute('data-tk-set'); c.setAttribute('data-clone', ''); c.setAttribute('aria-hidden', 'true');
			[].forEach.call(c.querySelectorAll('a'), function (a) { a.tabIndex = -1; });
			track.appendChild(c);
		}
		view.style.setProperty('--tk-w', w + 'px');
		view.style.setProperty('--tk-dur', (w / speed) + 's');
		if (!reduce) { view.classList.add('is-live'); }
	}
	build();
	var t; window.addEventListener('resize', function () { clearTimeout(t); t = setTimeout(build, 250); });
})();
</script>