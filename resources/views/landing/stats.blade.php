{{-- ======================= STATS =======================
     Needs: $sx_stats (counted from the database in landing/index)
     Labels / optional "years" figure: config/landing.php (stats)
     Hidden automatically when there is nothing to show.
     Look: public/assets/main/css/sasa-stats.css   Behaviour: public/assets/main/js/sasa-stats.js
     (The count-up of the numbers is done by the small script at the bottom of landing/index.blade.php.) --}}
@if (count($sx_stats) > 0)
<section class="sx-nm" id="stats" data-sx-numbers>
	<span class="sx-nm__orb sx-nm__orb--a" aria-hidden="true"></span>
	<span class="sx-nm__orb sx-nm__orb--b" aria-hidden="true"></span>
	<div class="sx-container sx-nm__wrap">
		<div class="sx-nm__head">
			<span class="sx-nm__eyebrow">{{ config('landing.stats.eyebrow') }}</span>
			<h2 class="sx-nm__title">{{ config('landing.stats.title') }}</h2>
		</div>
		<div class="sx-nm__grid">
			@foreach ($sx_stats as $k => $st)
			<div class="sx-nm__item" style="--i: {{ $k }}">
				<div class="sx-nm__card">
					<span class="sx-nm__icon"><x-site.icon :name="$st['icon']" /></span>
					<strong class="sx-nm__num" data-sx-count="{{ $st['value'] }}">{{ $st['value'] }}</strong>
					<span class="sx-nm__label">{{ $st['label'] }}</span>
					<span class="sx-nm__line" aria-hidden="true"></span>
				</div>
			</div>
			@endforeach
		</div>
	</div>
</section>
@endif