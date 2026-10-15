@if($nav['prev'] || $nav['next'])
<section class="sxi-section" style="padding-top:0">
	<div class="sxi-wrap">
		<div class="sxa-pn">
			@if($nav['prev'])
			<a class="sxa-pn__a sxi-reveal" href="{{ url('/' . $nav['prev']['slug'] . '.html') }}"><small>Previous</small><strong>{{ $nav['prev']['name'] }}</strong><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M11 6l-6 6 6 6"/></svg></a>
			@else<span></span>@endif
			@if($nav['next'])
			<a class="sxa-pn__a sxa-pn__a--next sxi-reveal" href="{{ url('/' . $nav['next']['slug'] . '.html') }}"><small>Next</small><strong>{{ $nav['next']['name'] }}</strong><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg></a>
			@endif
		</div>
	</div>
</section>
@endif
