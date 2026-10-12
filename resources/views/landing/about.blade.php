{{-- ======================= ABOUT =======================
     Needs: $data_printer (CMS id 8), $data_cms (CMS id 1, from WelcomeController), $sx_link
     Content: Admin > Pages. Button + video: config/landing.php (about) --}}
@php
$sx_about = count($data_printer) > 0 ? $data_printer[0] : null;
$sx_home  = (!empty($data_cms) && (!empty($data_cms['heading']) || !empty($data_cms['full_contents']))) ? $data_cms : null;
@endphp

@if ($sx_about || $sx_home)
<section class="sx-section sx-section--ivory sx-about" id="about">
	<div class="sx-container">

		@if ($sx_about)
		<div class="sx-about__grid">
			<div class="sx-about__text sx-reveal">
				@if (!empty($sx_about['tag_line']))<span class="sx-eyebrow">{{ $sx_about['tag_line'] }}</span>@endif
				<h2 class="sx-title">{!! $sx_about['heading'] !!}</h2>
				<div class="sx-prose">{!! $sx_about['short_contents'] !!}</div>
				<div class="sx-actions">
					<a href="{{ $sx_link(config('landing.about.cta_link')) }}" class="sx-btn sx-btn--primary">{{ config('landing.about.cta_label') }} <x-site.icon name="arrow" /></a>
				</div>
			</div>
			@if (config('landing.about.video_url'))
			<div class="sx-media-wrap sx-reveal">
				<div class="sx-media">
					<iframe src="{{ config('landing.about.video_url') }}" title="{{ config('landing.about.video_title') }}" loading="lazy" referrerpolicy="strict-origin-when-cross-origin" allow="accelerometer; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
				</div>
			</div>
			@endif
		</div>
		@endif

		@if ($sx_home)
		<div class="sx-more"@unless ($sx_about) style="margin-top:0;padding-top:0;border-top:0"@endunless>
			<x-site.split-block :tag="(string) $sx_home['tag_line']" :heading="(string) $sx_home['heading']" :html="(string) $sx_home['full_contents']" :img="(string) ($sx_home['banner'] ?? '')" />
		</div>
		@endif

	</div>
</section>
@endif
