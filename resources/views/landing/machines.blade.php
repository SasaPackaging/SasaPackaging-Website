{{-- ======================= MACHINES =======================
     Machines list + wording: config/landing.php (machines)
     Photos go in public/upload/machines/ --}}
@php
$sx_machines = config('landing.machines.items', []);
@endphp
<section class="sx-section sx-section--sage" id="machines">
	<div class="sx-container">
		<x-site.section-head :eyebrow="config('landing.machines.eyebrow')" :title="config('landing.machines.title')" :lead="config('landing.machines.lead')" />
		<div class="sx-grid-3">
			@forelse ($sx_machines as $mc)
			<article class="sx-card sx-machine sx-reveal">
				<div class="sx-machine__img">
					@if (!empty($mc['image']))
					<img src="{{ url('/public/upload/machines/' . $mc['image']) }}" alt="{{ $mc['name'] }}" loading="lazy">
					@else
					<div class="sx-ph"><x-site.icon name="sliders" /></div>
					@endif
				</div>
				<div class="sx-machine__body">
					<h3>{{ $mc['name'] }}</h3>
					@if (!empty($mc['desc']))<p>{{ $mc['desc'] }}</p>@endif
				</div>
			</article>
			@empty
			@for ($n = 1; $n <= (int) config('landing.machines.placeholder_count'); $n++)
			<article class="sx-card sx-machine sx-machine--ph sx-reveal">
				<div class="sx-machine__img"><div class="sx-ph"><x-site.icon name="sliders" /><span>{{ config('landing.machines.placeholder_photo') }}</span></div></div>
				<div class="sx-machine__body">
					<h3>{{ config('landing.machines.placeholder_name') }}</h3>
					<p>{{ config('landing.machines.placeholder_text') }}</p>
				</div>
			</article>
			@endfor
			@endforelse
		</div>
	</div>
</section>
