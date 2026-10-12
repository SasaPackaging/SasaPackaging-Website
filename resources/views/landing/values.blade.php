{{-- ======================= MISSION & VALUES =======================
     Needs: $features_dp   Content: Admin > Services   Heading: config/landing.php (values) --}}
@if (count($features_dp) > 0)
<section class="sx-section sx-section--blush" id="mission">
	<div class="sx-container">
		<x-site.section-head :eyebrow="config('landing.values.eyebrow')" :title="config('landing.values.title')" :lead="config('landing.values.lead')" />
		<div class="sx-values">
			@foreach ($features_dp as $row)
			<article class="sx-card sx-value sx-reveal">
				<div class="sx-value__icon">
					@if (!empty($row['icon_class']))
					<img src="{{ url('/public/upload/services/' . $row['icon_class']) }}" alt="" loading="lazy">
					@else
					<x-site.icon name="check" />
					@endif
				</div>
				<h3>{{ $row['name'] }}</h3>
				<p class="sx-clamp-4">{{ strip_tags((string) $row['FullContents']) }}</p>
			</article>
			@endforeach
		</div>
	</div>
</section>
@endif
