{{-- One image tile.  Needs: $row (db row array), $i (index), $kind ('product'|'category')
     Optional: $tag (small label), $count (shown as a pill), $feature (bool), $cat (category id for filtering) --}}
@php
$sxi_kind = $kind ?? 'product';
$sxi_src = sxi_img($row['slug'] ?? '', $row['image'] ?? '', $sxi_kind === 'category' ? 'category' : 'products');
$sxi_span = [14, 10, 12, 16, 11, 13, 10, 15, 12, 14][$i % 10];
$sxi_href = url('/' . ltrim((string) ($row['slug'] ?? ''), '/') . '.html');
@endphp
<a href="{{ $sxi_href }}"
   class="sxi-tile sxi-c{{ $i % 6 }} sxi-reveal{{ ($feature ?? false) ? ' is-feature' : '' }}{{ $sxi_kind === 'category' ? ' is-card' : '' }}"
   style="--span:{{ $sxi_span }};--d:{{ ($i % 8) * 70 }}ms"
   @if(isset($cat)) data-cat="{{ $cat }}" @endif
   aria-label="{{ $row['name'] }}">
	<span class="sxi-media{{ $sxi_src === '' ? ' is-empty' : '' }}">
		@if($sxi_src !== '')
			<img src="{{ $sxi_src }}" alt="{{ $row['name'] }}" loading="lazy" decoding="async"
				onerror="this.parentNode.classList.add('is-empty');this.remove()">
		@endif
		<span class="sxi-ph" aria-hidden="true">{!! sxi_icon() !!}</span>
	</span>
	<span class="sxi-idx">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
	@if(!empty($count))<span class="sxi-pill">{{ $count }} {{ $count == 1 ? 'item' : 'items' }}</span>@endif
	<span class="sxi-cap">
		<span class="sxi-cap__t">
			@if(!empty($tag))<small>{{ $tag }}</small>@endif
			<strong>{{ $row['name'] }}</strong>
		</span>
		<span class="sxi-go" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17 17 7M8 7h9v9"/></svg></span>
	</span>
</a>
