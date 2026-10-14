{{-- ======================= NEWS & EVENTS (sage background) =======================
     Save as resources/views/landing/news.blade.php
     Needs: $sx_news (latest posts, set in landing/index.blade.php)
     Wording + chips: config/landing.php > news  (eyebrow, title, lead, topics, empty_title, empty_text)
     Look: public/assets/main/css/sasa-trust.css --}}
@php
$nw_topics = (array) config('landing.news.topics', ['Exhibitions', 'Certifications', 'New openings']);
@endphp
<section class="nw sxt" id="news-events" aria-labelledby="nw-title" data-nw>
	<div class="nw__bg" aria-hidden="true">
		<span class="nw__orb nw__orb--a"></span><span class="nw__orb nw__orb--b"></span>
		@for ($d = 0; $d < 14; $d++)<i style="--x: {{ ($d * 83) % 100 }}%; --y: {{ ($d * 47 + 11) % 100 }}%; --d: {{ 6 + ($d % 5) }}s; --s: {{ 8 + ($d % 4) * 4 }}px; --w: {{ $d * 0.4 }}s"></i>@endfor
	</div>

	<div class="sx-container">
		<header class="sxt-head sxt-head--on-sage">
			<span class="sxt-eyebrow">{{ config('landing.news.eyebrow', 'From SASA Packaging') }}</span>
			<h2 class="sxt-title" id="nw-title">{!! config('landing.news.title', 'News &amp; Events') !!}</h2>
			<p class="sxt-lead">{!! config('landing.news.lead', 'Exhibitions, certifications and new openings — the latest from SASA Packaging.') !!}</p>
			@if (count($nw_topics) > 0)
			<ul class="nw__chips">
				@foreach ($nw_topics as $k => $tp)<li style="--k: {{ $k }}">{{ $tp }}</li>@endforeach
			</ul>
			@endif
		</header>

		@if (count($sx_news) > 0)
		<div class="nw__list">
			@foreach ($sx_news as $k => $post)
			@php
				$n_ts   = !empty($post['post_date']) ? strtotime($post['post_date']) : false;
				$n_text = isset($post['Contents']) ? trim(strip_tags(html_entity_decode((string) $post['Contents']))) : '';
				$n_href = !empty($post['slug']) ? url('/blog/' . $post['slug']) : '';
			@endphp
			<article class="nw__card" style="--k: {{ $k }}">
				<div class="nw__img">
					@if (!empty($post['image']))
						<img src="{{ url('/public/upload/post/' . $post['image']) }}" alt="" loading="lazy">
					@else
						<span class="nw__ph" aria-hidden="true"><x-site.icon name="image" /></span>
					@endif
					@if ($n_ts)
					<time class="nw__date" datetime="{{ date('Y-m-d', $n_ts) }}"><b>{{ date('d', $n_ts) }}</b>{{ date('M Y', $n_ts) }}</time>
					@endif
					<i class="nw__sheen" aria-hidden="true"></i>
				</div>
				<div class="nw__body">
					<h3 class="nw__title">@if ($n_href !== '')<a href="{{ $n_href }}" class="nw__link">{{ $post['heading'] }}</a>@else{{ $post['heading'] }}@endif</h3>
					@if ($n_text !== '')<p class="nw__text">{{ $n_text }}</p>@endif
					@if ($n_href !== '')<span class="nw__more" aria-hidden="true">Read more <x-site.icon name="arrow" /></span>@endif
				</div>
			</article>
			@endforeach
		</div>
		@else
		<div class="nw__empty">
			<div class="nw__megaphone" aria-hidden="true">
				<svg viewBox="0 0 120 120" fill="none" stroke="currentColor" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round">
					<path d="M24 50h14l34-20v60L38 70H24z"/><path d="M38 70l6 22h12l-6-22"/>
					<path class="nw__wave nw__wave--1" d="M84 46c6 6 6 22 0 28"/><path class="nw__wave nw__wave--2" d="M94 36c11 11 11 37 0 48"/>
				</svg>
			</div>
			<h3>{{ config('landing.news.empty_title', 'No updates yet') }}</h3>
			<p>{{ config('landing.news.empty_text', 'Exhibitions we attend, certifications we earn and new openings will be announced right here. Stay tuned.') }}</p>
		</div>
		@endif
	</div>
</section>

<script>
(function () {
	var s = document.querySelector('[data-nw]');
	if (!s) { return; }
	if (!('IntersectionObserver' in window)) { return; }
	s.classList.add('is-armed');
	var io = new IntersectionObserver(function (e) { if (e[0].isIntersecting) { s.classList.add('is-in'); io.disconnect(); } }, { threshold: 0.15 });
	io.observe(s);
})();
</script>