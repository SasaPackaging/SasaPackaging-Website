{{-- Text + optional image side by side. Used by About, Services and Lamination.
     Usage: <x-site.split-block :tag="..." :heading="..." :html="..." :img="..." :reverse="false" /> --}}
@props(['tag' => '', 'heading' => '', 'html' => '', 'img' => '', 'reverse' => false])
<div class="sx-split{{ $reverse ? ' is-reverse' : '' }}{{ $img === '' ? ' sx-split--solo' : '' }} sx-reveal">
	@if ($img !== '')
	<div class="sx-split__media"><img src="{{ url('/public/upload/cms/' . $img) }}" alt="{{ strip_tags((string) $heading) }}" loading="lazy"></div>
	@endif
	<div>
		@if ($tag !== '')<span class="sx-eyebrow">{{ $tag }}</span>@endif
		@if ($heading !== '')<h2 class="sx-title">{!! $heading !!}</h2>@endif
		<div class="sx-prose">{!! $html !!}</div>
	</div>
</div>
