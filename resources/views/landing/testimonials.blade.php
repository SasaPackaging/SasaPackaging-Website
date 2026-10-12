{{-- ======================= TESTIMONIALS + PARTNERS =======================
     Needs: $members_dp (Admin > Members = partner logos)
     Reviews + wording: config/landing.php (testimonials) --}}
@php
$sx_reviews = config('landing.testimonials.items', []);
@endphp
<section class="sx-section sx-section--blush" id="ratings">
	<div class="sx-container">
		<x-site.section-head :eyebrow="config('landing.testimonials.eyebrow')" :title="config('landing.testimonials.title')" />

		@if (count($sx_reviews) > 0)
		<div class="sx-grid-3">
			@foreach ($sx_reviews as $rv)
			<figure class="sx-card sx-quote sx-reveal" style="margin:0">
				<blockquote>&ldquo;{{ $rv['quote'] }}&rdquo;</blockquote>
				<figcaption><cite>{{ $rv['name'] }}@if (!empty($rv['role']))<small>{{ $rv['role'] }}</small>@endif</cite></figcaption>
			</figure>
			@endforeach
		</div>
		@else
		<div class="sx-card sx-empty sx-reveal"><div class="sx-empty__icon"><x-site.icon name="quote" /></div><div><h3>{{ config('landing.testimonials.empty_title') }}</h3><p>{{ config('landing.testimonials.empty_text') }}</p></div></div>
		@endif

		@if (count($members_dp) > 0)
		<div class="sx-partners">
			<h3>{{ config('landing.testimonials.partners_title') }}</h3>
			<ul class="sx-logos">
				@foreach ($members_dp as $row)
				<li>
					@if (!empty($row['slug']))
					<a href="{{ $row['slug'] }}"><img src="{{ url('/public/upload/members/' . $row['image']) }}" alt="{{ $row['name'] }}" loading="lazy"></a>
					@else
					<span><img src="{{ url('/public/upload/members/' . $row['image']) }}" alt="{{ $row['name'] }}" loading="lazy"></span>
					@endif
				</li>
				@endforeach
			</ul>
		</div>
		@endif
	</div>
</section>
