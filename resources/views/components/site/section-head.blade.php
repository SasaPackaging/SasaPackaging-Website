{{-- Usage: <x-site.section-head :eyebrow="..." :title="..." :lead="..." />
     $title and $lead may contain trusted HTML from the admin (same as before). --}}
@props(['eyebrow' => '', 'title' => '', 'lead' => '', 'left' => false])
@if ($eyebrow !== '' || $title !== '' || $lead !== '')
<div {{ $attributes->class(['sx-head', 'sx-head--left' => $left, 'sx-reveal']) }}>
	@if ($eyebrow !== '')<span class="sx-eyebrow">{{ $eyebrow }}</span>@endif
	@if ($title !== '')<h2 class="sx-title">{!! $title !!}</h2>@endif
	@if ($lead !== '')<div class="sx-lead">{!! $lead !!}</div>@endif
</div>
@endif
