{{-- ======================= STATS =======================
     Needs: $sx_stats (counted from the database in landing/index)
     Labels / optional "years" figure: config/landing.php (stats)
     Hidden automatically when there is nothing to show. --}}
@if (count($sx_stats) > 0)
<section class="sx-section sx-stats" id="stats">
	<div class="sx-container">
		<div class="sx-head sx-reveal">
			<span class="sx-eyebrow">{{ config('landing.stats.eyebrow') }}</span>
			<h2 class="sx-title">{{ config('landing.stats.title') }}</h2>
		</div>
		<div class="sx-stats__grid">
			@foreach ($sx_stats as $st)
			<div class="sx-stat sx-reveal">
				<span class="sx-stat__icon"><x-site.icon :name="$st['icon']" /></span>
				<strong class="sx-stat__num" data-sx-count="{{ $st['value'] }}">{{ $st['value'] }}</strong>
				<span class="sx-stat__label">{{ $st['label'] }}</span>
			</div>
			@endforeach
		</div>
	</div>
</section>
@endif
