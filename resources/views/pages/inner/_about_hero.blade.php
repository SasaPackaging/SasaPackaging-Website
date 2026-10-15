{{-- Needs: $h_crumbs [[label,url|null],...], $h_eyebrow, $h_title, $h_lead, $h_imgs (0-3 urls), $h_kind
     Optional: $h_cta [label,url] --}}
@php $h_imgs = array_values(array_filter((array) ($h_imgs ?? []))); $h_n = count($h_imgs); @endphp
<section class="sxi-hero">
	<span class="sxi-float sxi-float--a" data-depth="26"></span>
	<span class="sxi-float sxi-float--b" data-depth="-18"></span>
	<span class="sxi-float sxi-float--c" data-depth="34"></span>
	<div class="sxi-wrap sxi-hero__grid">
		<div>
			<ul class="sxi-crumbs">
				@foreach($h_crumbs as $cr)
					<li>@if(!empty($cr[1]))<a href="{{ $cr[1] }}">{{ $cr[0] }}</a>@else{{ $cr[0] }}@endif</li>
				@endforeach
			</ul>
			@if(!empty($h_eyebrow))<span class="sxi-eyebrow">{{ $h_eyebrow }}</span>@endif
			<h1 class="sxi-h1" data-split>{{ $h_title }}</h1>
			@if(!empty($h_lead))<p class="sxi-lead">{{ $h_lead }}</p>@endif
			@if(!empty($h_cta))
				<div class="sxi-actions"><a class="sxi-btn sxi-btn--dark" href="{{ $h_cta[1] }}">{{ $h_cta[0] }} <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M6 13l6 6 6-6"/></svg></a></div>
			@endif
		</div>
		<div class="sxi-collage" aria-hidden="true">
			@if($h_n >= 2)
				@foreach(array_slice($h_imgs, 0, 3) as $hi => $hu)
					<div class="sxi-arch sxi-arch--{{ $hi + 1 }} sxi-c{{ $hi * 2 }}" data-depth="{{ [14, -22, 30][$hi] }}">
						<img src="{{ $hu }}" alt="" onerror="this.nextElementSibling.classList.add('is-on');this.remove()"><span class="sxi-ph">{!! sxa_icon($h_kind ?? 'default') !!}</span>
					</div>
				@endforeach
			@else
				<div class="sxi-arch sxi-arch--solo sxi-c{{ $h_n ? 0 : 2 }}" data-depth="18">
					@if($h_n)<img src="{{ $h_imgs[0] }}" alt="" onerror="this.nextElementSibling.classList.add('is-on');this.remove()">@endif
					<span class="sxi-ph sxa-ph-big {{ $h_n ? '' : 'is-on' }}">{!! sxa_icon($h_kind ?? 'default') !!}</span>
				</div>
			@endif
			<div class="sxi-badge"><i>{!! sxa_icon($h_kind ?? 'default') !!}</i><span><b>{{ $h_eyebrow ?: 'SASA Packaging' }}</b>Who we are</span></div>
		</div>
	</div>
</section>
