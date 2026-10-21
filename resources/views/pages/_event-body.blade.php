{{-- One event's details: title (when linked), date / place, story, video, picture grid.
     Needs: $e (NewsEvent), $page (config news.events_page). Optional: $link (title links to ?event=slug) --}}
@php $urls = $e->image_urls; $video = $e->video; @endphp
<div class="evp-meta">
	<time>{{ $e->shown_date->format('d F Y') }}</time>
	@if ($e->location)<span>{{ $e->location }}</span>@endif
</div>

@if (!empty($link))
	<h3 class="evp-title"><a href="{{ url(config('news.events_url')) }}?event={{ $e->slug }}">{{ $e->title }}</a></h3>
@endif

@if ($e->summary && !empty($link))<p class="evp-sum">{{ $e->summary }}</p>@endif

@if (trim(strip_tags((string) $e->body)) !== '')
	<div class="sxi-prose sxa-prose" data-prose>{!! $e->body !!}</div>
@endif

@if ($video)
<div class="evp-video">
	@if ($video['mode'] === 'iframe')
		<iframe src="{{ $video['src'] }}" title="{{ $e->title }}" loading="lazy" allowfullscreen allow="accelerometer; encrypted-media; picture-in-picture"></iframe>
	@else
		<video src="{{ $video['src'] }}" controls preload="metadata"></video>
	@endif
</div>
@endif

@if (count($urls) > 0)
<div class="evp-gal">
	@foreach ($urls as $u)
		<a href="{{ $u }}" target="_blank" rel="noopener"><img src="{{ $u }}" alt="{{ $e->title }}" loading="lazy"></a>
	@endforeach
</div>
@endif

@if (!empty($link))
	<p class="evp-more"><a class="sxi-btn sxi-btn--line" href="{{ url(config('news.events_url')) }}?event={{ $e->slug }}">{{ $page['view_event'] }}</a></p>
@endif
