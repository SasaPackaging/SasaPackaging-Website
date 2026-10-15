{{-- pages\inner\_certificates.blade.php
     Certificates block for the About section: one clean detail card per certificate + full-size viewer.
     (The landing-page style shield + confetti section was removed on purpose. The landing page keeps its own.)
     Needs: $certs (from sxa_certs()) --}}
@if(count($certs) > 0)

{{-- Hidden gradient used by the small seal on each card (.sxa-cc__seal). Keep this, the seal needs it. --}}
<svg width="0" height="0" style="position:absolute" aria-hidden="true" focusable="false">
	<defs><linearGradient id="ctGrad" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#ffffff"/><stop offset=".55" stop-color="#eef4ec"/><stop offset="1" stop-color="#B8CFB3"/></linearGradient></defs>
</svg>

{{-- detail cards --}}
<section class="sxi-section sxi-section--white" id="certificates">
	<div class="sxi-wrap">
		<div class="sxi-secthead">
			<div><span class="sxi-eyebrow">In detail</span><h2>Our certificates</h2></div>
			<p>What each certificate stands for. Press a certificate to see it full size.</p>
		</div>
		<div class="sxa-certs">
			@foreach($certs as $i => $c)
			@php $view = $c['doc'] !== '' ? $c['doc'] : $c['image']; @endphp
			<article class="sxa-cc sxi-reveal sxi-c{{ $i % 6 }}" style="--d:{{ ($i % 4) * 90 }}ms">
				<div class="sxa-cc__doc {{ $view === '' ? 'is-empty' : '' }}" @if($view !== '') data-cert-view data-src="{{ $view }}" tabindex="0" role="button" aria-label="View {{ $c['name'] }}" @endif>
					@if($view !== '')<img src="{{ $view }}" alt="{{ $c['name'] }}" loading="lazy">@endif
					<span class="sxa-cc__seal"><svg viewBox="0 0 200 240" aria-hidden="true"><path d="M100 8 L184 38 V112 C184 168 148 208 100 230 C52 208 16 168 16 112 V38 Z" fill="url(#ctGrad)" stroke="#84a282" stroke-width="5"/></svg><b>{{ $c['short'] }}</b></span>
				</div>
				<div class="sxa-cc__body">
					<div class="sxa-cc__chips">
						<span class="sxi-tag">Certified</span>
						@if($c['issuer'] !== '')<span class="sxi-tag sxi-tag--blush">{{ $c['issuer'] }}</span>@endif
						@if($c['year'] !== '')<span class="sxi-tag sxi-tag--blush">{{ $c['year'] }}</span>@endif
					</div>
					<h3>{{ $c['name'] }}</h3>
					@if($c['note'] !== '')<p>{{ $c['note'] }}</p>@endif
					@if($view !== '')<button type="button" class="sxi-btn sxi-btn--line" data-cert-view data-src="{{ $view }}">View certificate <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg></button>@endif
				</div>
			</article>
			@endforeach
		</div>
	</div>
</section>

<div class="sxi-lb" data-cert-lb role="dialog" aria-modal="true" aria-label="Certificate viewer">
	<button type="button" class="sxi-lb__x" data-cert-x aria-label="Close">&times;</button>
	<img src="" alt="">
</div>
@else
<section class="sxi-section"><div class="sxi-wrap"><div class="sxi-empty sxi-empty--static"><p>Certificates will be shown here soon.</p></div></div></section>
@endif