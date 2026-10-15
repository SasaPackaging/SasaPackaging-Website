{{-- Needs: $nav (from sxa_nav) and $cur_id --}}
@if(count($nav['sibs']) > 1)
<div class="sxa-subnav-wrap"><div class="sxi-wrap">
	<nav class="sxa-subnav" aria-label="In this section">
		@foreach($nav['sibs'] as $s)
			<a href="{{ url('/' . $s['slug'] . '.html') }}" class="{{ (int) $s['id'] === (int) $cur_id ? 'is-current' : '' }}" {!! (int) $s['id'] === (int) $cur_id ? 'aria-current="page"' : '' !!}>{!! sxa_icon(sxa_kind($s['slug'], $s['name'])) !!}<span>{{ $s['name'] }}</span></a>
		@endforeach
	</nav>
</div></div>
@endif
