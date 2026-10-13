<!-- {{-- ======================= SERVICES (grid) =======================
     Needs: $data_services (CMS pages whose parent is id 24, status = Yes), $data_cms (CMS id 1)
     Content: Admin > Pages. Section heading: config/landing.php (services)
     Look: public/assets/main/css/sasa-services.css   Behaviour: public/assets/main/js/sasa-services.js

     Nothing is hard-coded: every card is a CMS page. A page shows here when
       - its status is "Yes", and
       - (once the admin adds it) its 'show_on_home' field is not "No".
     Card image = the page's 'banner' file, folder: config('landing.services.image_dir', 'upload/cms').
     No image / image missing = a soft tile with an icon instead (never a broken picture).
     Card colours (sage / blush / deep sage) are set in the CSS so that no two neighbours share a colour. --}}
@php
$sx_sv_dir = trim((string) config('landing.services.image_dir', 'upload/cms'), '/');

/* turn one CMS row into a card */
$sx_sv_card = function ($r) use ($sx_sv_dir) {
	$show = isset($r['show_on_home']) ? strtolower(trim((string) $r['show_on_home'])) : 'yes';
	if (in_array($show, ['no', 'n', '0', 'false'], true)) { return null; }

	$heading = trim(strip_tags((string) ($r['heading'] ?? '')));
	if ($heading === '') { return null; }

	$desc = trim(strip_tags((string) ($r['short_contents'] ?? '')));
	if ($desc === '') { $desc = trim(strip_tags(html_entity_decode((string) ($r['full_contents'] ?? '')))); }
	$desc = preg_replace('/\s+/', ' ', $desc);
	$desc = mb_strimwidth($desc, 0, 230, '…');

	$img = trim((string) ($r['banner'] ?? ''));
	if ($img !== '' && !preg_match('~^(https?:)?//~i', $img)) {
		$img = url('/public/' . $sx_sv_dir . '/' . ltrim($img, '/'));
	}
	$tag = trim(strip_tags((string) ($r['tag_line'] ?? '')));
	if (mb_strtolower($tag) === mb_strtolower($heading)) { $tag = ''; }   // don't print the same words twice
	return ['tag' => $tag, 'title' => $heading, 'desc' => $desc, 'img' => $img];
};

$sx_cards = [];
// the CMS id 1 block ("PRINTING SERVICES") goes first, like a normal service
if (config('landing.services.include_home_block', true) && !empty($data_cms)) {
	$c = $sx_sv_card((array) $data_cms);
	if ($c) { $sx_cards[] = $c; }
}
foreach ($data_services as $row) {
	$c = $sx_sv_card((array) $row);
	if ($c) { $sx_cards[] = $c; }
}
@endphp

@if (count($sx_cards) > 0)
<section class="sx-section sx-sv" id="services" data-sx-services>
	<div class="sx-container">
		<x-site.section-head :eyebrow="config('landing.services.eyebrow')" :title="config('landing.services.title')" :lead="config('landing.services.lead')" />

		<div class="sx-sv__grid">
			@foreach ($sx_cards as $k => $sv)
			<div class="sx-sv__item" style="--i: {{ $k % 3 }}">
				<article class="sx-sv__card">
					<div class="sx-sv__media{{ $sv['img'] !== '' ? ' has-img' : '' }}">
						@if ($sv['img'] !== '')
						<img src="{{ $sv['img'] }}" alt="{{ $sv['title'] }}" loading="lazy" decoding="async" onerror="this.parentNode.classList.add('is-empty'); this.remove();">
						@endif
						<span class="sx-sv__ph" aria-hidden="true"><x-site.icon name="cube" /></span>
						<span class="sx-sv__num">{{ str_pad($k + 1, 2, '0', STR_PAD_LEFT) }}</span>
					</div>
					<div class="sx-sv__body">
						@if ($sv['tag'] !== '')<span class="sx-sv__tag">{{ $sv['tag'] }}</span>@endif
						<h3 class="sx-sv__title">{{ $sv['title'] }}</h3>
						@if ($sv['desc'] !== '')<p class="sx-sv__text">{{ $sv['desc'] }}</p>@endif
					</div>
					<span class="sx-sv__bar" aria-hidden="true"></span>
				</article>
			</div>
			@endforeach
		</div>
	</div>
</section>
@endif -->

{{-- ======================= SERVICES (grid) =======================
     Needs: $data_services (CMS pages whose parent is id 24, status = Yes), $data_cms (CMS id 1), $data_paper (CMS id 7)
     Content: Admin > Pages. Section heading: config/landing.php (services)
     Look: public/assets/main/css/sasa-services.css   Behaviour: public/assets/main/js/sasa-services.js

     Nothing is hard-coded: every card is a CMS page. A page shows here when
       - its status is "Yes", and
       - (once the admin adds it) its 'show_on_home' field is not "No".
     Card image = the page's 'banner' file, folder: config('landing.services.image_dir', 'upload/cms').
     No image / image missing = a soft tile with an icon instead (never a broken picture).
     Card colours (sage / blush / deep sage) are set in the CSS so that no two neighbours share a colour. --}}
@php
$sx_sv_dir = trim((string) config('landing.services.image_dir', 'upload/cms'), '/');

/* turn one CMS row into a card */
$sx_sv_card = function ($r) use ($sx_sv_dir) {
	$show = isset($r['show_on_home']) ? strtolower(trim((string) $r['show_on_home'])) : 'yes';
	if (in_array($show, ['no', 'n', '0', 'false'], true)) { return null; }

	$heading = trim(strip_tags((string) ($r['heading'] ?? '')));
	if ($heading === '') { return null; }

	$desc = trim(strip_tags((string) ($r['short_contents'] ?? '')));
	if ($desc === '') { $desc = trim(strip_tags(html_entity_decode((string) ($r['full_contents'] ?? '')))); }
	$desc = preg_replace('/\s+/', ' ', $desc);
	$desc = mb_strimwidth($desc, 0, 230, '…');

	$img = trim((string) ($r['banner'] ?? ''));
	if ($img !== '' && !preg_match('~^(https?:)?//~i', $img)) {
		$img = url('/public/' . $sx_sv_dir . '/' . ltrim($img, '/'));
	}
	$tag = trim(strip_tags((string) ($r['tag_line'] ?? '')));
	if (mb_strtolower($tag) === mb_strtolower($heading)) { $tag = ''; }   // don't print the same words twice
	return ['tag' => $tag, 'title' => $heading, 'desc' => $desc, 'img' => $img];
};

$sx_cards = [];
// the CMS id 1 block ("PRINTING SERVICES") goes first, like a normal service
if (config('landing.services.include_home_block', true) && !empty($data_cms)) {
	$c = $sx_sv_card((array) $data_cms);
	if ($c) { $sx_cards[] = $c; }
}
foreach ($data_services as $row) {
	$c = $sx_sv_card((array) $row);
	if ($c) { $sx_cards[] = $c; }
}
// never show the same service twice (same heading = same card, first one wins)
$sx_seen = [];
$sx_cards = array_values(array_filter($sx_cards, function ($c) use (&$sx_seen) {
	$key = mb_strtolower($c['title']);
	if (isset($sx_seen[$key])) { return false; }
	$sx_seen[$key] = true;
	return true;
}));
@endphp

@if (count($sx_cards) > 0)
<section class="sx-section sx-sv" id="services" data-sx-services>
	<div class="sx-container">
		<x-site.section-head :eyebrow="config('landing.services.eyebrow')" :title="config('landing.services.title')" :lead="config('landing.services.lead')" />

		<div class="sx-sv__grid">
			@foreach ($sx_cards as $k => $sv)
			<div class="sx-sv__item" style="--i: {{ $k % 3 }}">
				<article class="sx-sv__card">
					<div class="sx-sv__media{{ $sv['img'] !== '' ? ' has-img' : '' }}">
						@if ($sv['img'] !== '')
						<img src="{{ $sv['img'] }}" alt="{{ $sv['title'] }}" loading="lazy" decoding="async" onerror="this.parentNode.classList.add('is-empty'); this.remove();">
						@endif
						<span class="sx-sv__ph" aria-hidden="true"><x-site.icon name="cube" /></span>
						<span class="sx-sv__num">{{ str_pad($k + 1, 2, '0', STR_PAD_LEFT) }}</span>
					</div>
					<div class="sx-sv__body">
						@if ($sv['tag'] !== '')<span class="sx-sv__tag">{{ $sv['tag'] }}</span>@endif
						<h3 class="sx-sv__title">{{ $sv['title'] }}</h3>
						@if ($sv['desc'] !== '')<p class="sx-sv__text">{{ $sv['desc'] }}</p>@endif
					</div>
					<span class="sx-sv__bar" aria-hidden="true"></span>
				</article>
			</div>
			@endforeach
		</div>
	</div>
</section>
@endif