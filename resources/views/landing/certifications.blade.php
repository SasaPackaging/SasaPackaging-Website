{{-- ======================= CERTIFICATIONS =======================
     Needs: $certificate_page (CMS page id 20)
     Content: Admin > Pages (id 20). Wording: config/landing.php (certifications) --}}
<section class="sx-section sx-section--ivory" id="certifications">
	<div class="sx-container">
		<x-site.section-head :eyebrow="config('landing.certifications.eyebrow')" :title="config('landing.certifications.title')" :lead="config('landing.certifications.lead')" />

		@if ($certificate_page)
		@php $c_title = $certificate_page->heading ?: $certificate_page->name; @endphp
		<div class="sx-card sx-cert sx-reveal">
			@if (!empty($certificate_page->banner))
			<div class="sx-cert__img"><img src="{{ url('/public/upload/cms/' . $certificate_page->banner) }}" alt="{{ strip_tags((string) $c_title) }}" loading="lazy"></div>
			@endif
			<div>
				<h3>{{ strip_tags((string) $c_title) }}</h3>
				@if (!empty($certificate_page->short_contents))<div class="sx-prose">{!! $certificate_page->short_contents !!}</div>@endif
				@if (!empty($certificate_page->full_contents))<div class="sx-prose">{!! $certificate_page->full_contents !!}</div>@endif
			</div>
		</div>
		@else
		<div class="sx-cert-slots">
			@for ($n = 1; $n <= (int) config('landing.certifications.placeholder_count'); $n++)
			<div class="sx-slot sx-reveal"><x-site.icon name="badge" /><strong>{{ config('landing.certifications.placeholder_title') }}</strong><span>{{ config('landing.certifications.placeholder_text') }}</span></div>
			@endfor
		</div>
		@endif
	</div>
</section>
