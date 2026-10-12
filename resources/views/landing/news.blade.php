{{-- ======================= NEWS & EVENTS =======================
     Needs: $sx_news (latest posts)   Wording + how many: config/landing.php (news) --}}
<section class="sx-section sx-section--white" id="news-events">
	<div class="sx-container">
		<x-site.section-head :eyebrow="config('landing.news.eyebrow')" :title="config('landing.news.title')" :lead="config('landing.news.lead')" />

		@if (count($sx_news) > 0)
		<div class="sx-grid-3">
			@foreach ($sx_news as $post)
			@php
				$n_ts   = !empty($post['post_date']) ? strtotime($post['post_date']) : false;
				$n_text = isset($post['Contents']) ? trim(strip_tags((string) $post['Contents'])) : '';
			@endphp
			<article class="sx-card sx-news sx-reveal">
				<div class="sx-news__img">
					@if (!empty($post['image']))
					<img src="{{ url('/public/upload/post/' . $post['image']) }}" alt="" loading="lazy">
					@else
					<div class="sx-ph"><x-site.icon name="image" /></div>
					@endif
				</div>
				<div class="sx-news__body">
					@if ($n_ts)<time datetime="{{ date('Y-m-d', $n_ts) }}">{{ date('d M Y', $n_ts) }}</time>@endif
					<h3>{{ $post['heading'] }}</h3>
					@if ($n_text != '')<p class="sx-clamp-3">{{ $n_text }}</p>@endif
				</div>
			</article>
			@endforeach
		</div>
		@else
		<div class="sx-card sx-empty sx-reveal"><div class="sx-empty__icon"><x-site.icon name="calendar" /></div><div><h3>{{ config('landing.news.empty_title') }}</h3><p>{{ config('landing.news.empty_text') }}</p></div></div>
		@endif
	</div>
</section>
