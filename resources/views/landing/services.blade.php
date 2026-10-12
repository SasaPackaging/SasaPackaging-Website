{{-- ======================= SERVICES =======================
     Needs: $data_services (CMS pages whose parent is id 24)
     Content: Admin > Pages. Section heading: config/landing.php (services) --}}
@if (count($data_services) > 0)
<section class="sx-section sx-section--white" id="services">
	<div class="sx-container">
		<x-site.section-head :eyebrow="config('landing.services.eyebrow')" :title="config('landing.services.title')" :lead="config('landing.services.lead')" />
		<div class="sx-more" style="margin-top:0;padding-top:0;border-top:0">
			@foreach ($data_services as $k => $svc)
			<x-site.split-block
				:tag="(string) $svc['tag_line']"
				:heading="(string) $svc['heading']"
				:html="(string) $svc['full_contents']"
				:img="(string) ($svc['banner'] ?? '')"
				:reverse="$k % 2 == 1" />
			@endforeach
		</div>
	</div>
</section>
@endif
