{{-- ======================= LAMINATION =======================
     Needs: $data_paper (CMS page id 7)
     Content: Admin > Pages (page id 7). Optional heading: config/landing.php (lamination) --}}
@if (count($data_paper) > 0)
<section class="sx-section sx-section--sage" id="lamination">
	<div class="sx-container">
		<x-site.section-head :eyebrow="config('landing.lamination.eyebrow')" :title="config('landing.lamination.title')" :lead="config('landing.lamination.lead')" />
		<x-site.split-block
			:tag="(string) $data_paper[0]['tag_line']"
			:heading="(string) $data_paper[0]['heading']"
			:html="(string) $data_paper[0]['full_contents']"
			:img="(string) ($data_paper[0]['banner'] ?? '')" />
	</div>
</section>
@endif
