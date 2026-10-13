{{-- ======================= HERO =======================
     LEFT  : text slider (Admin > Banners; fallback in config/landing.php). Only the text changes.
     RIGHT : product image gallery – independent, slow vertical loop, pure CSS.
     Needs (from landing/index): $banners, $sx_link, $sx_rich
     Look      : public/assets/main/css/sasa-hero.css
     Behaviour : public/assets/main/js/sasa-hero.js

     GALLERY IMAGES
     Folder: public/assets/main/images/hero/  (override in config/landing.php > hero > gallery)
     - scan = true  (default): every png/jpg/jpeg/webp/avif/gif in the folder is used, sorted by name.
                               Drop a new image in the folder and it appears. No code change.
     - scan = false          : only the names listed in 'files' are used, in that order. --}}
@php
/* ---------- 1. Text slides ---------- */
$sx_slides = [];
foreach ($banners as $row) {
	$sx_slides[] = [
		'tag'   => $row['tag_line'],
		'title' => $row['name'],
		'text'  => $row['detail'],
		'btn'   => $row['button_text'],
		'link'  => $row['link'],
		'btn2'  => '',
		'link2' => '',
	];
}
if (count($sx_slides) == 0) {
	foreach (config('landing.hero.fallback_slides') as $fb) {
		$sx_slides[] = $fb;
	}
}

/* ---------- 2. Gallery image list ---------- */
$g_cfg   = (array) config('landing.hero.gallery', []);
$g_dir   = trim(isset($g_cfg['dir']) ? $g_cfg['dir'] : 'assets/main/images/hero', '/');
$g_scan  = array_key_exists('scan', $g_cfg) ? (bool) $g_cfg['scan'] : true;
$g_cols  = max(2, min(6, (int) (isset($g_cfg['columns']) ? $g_cfg['columns'] : 4)));
$g_files = [];

if ($g_scan) {
	$abs = public_path($g_dir);
	if (is_dir($abs)) {
		foreach (scandir($abs) as $f) {
			if (preg_match('/\.(png|jpe?g|webp|avif|gif)$/i', $f)) { $g_files[] = $f; }
		}
		natcasesort($g_files);
		$g_files = array_values($g_files);
	}
}
if (count($g_files) == 0) {
	$g_files = isset($g_cfg['files']) ? array_values((array) $g_cfg['files']) : [];
}
if (count($g_files) == 0) {
	$g_files = ['a.png', 'b.png', 'c.png', 'd.png', 'e.png', 'f.png', 'g.png', 'h.png'];
}
$g_n = count($g_files);

/* ---------- 3. Build the columns ----------
   Each column = 6 tiles (photos + a few empty colour blocks), different heights per column.
   The 6 tiles are printed twice so the track can loop with no gap or jump. */
$g_heights = ['l', 's', 'm', 's', 'l', 'm'];     // tall, short, medium ...
$g_tones   = ['cream', 'blush', 'sage'];
$g_frames  = ['sage', 'blush'];               // thin photo border colours (cream is left out on purpose)
$g_durs    = [78, 92, 70, 100, 84, 96];          // seconds per loop, per column
$g_per     = 6;
$g_cols_data = [];
for ($c = 0; $c < $g_cols; $c++) { $g_cols_data[$c] = []; }
$cursor = 0;
for ($j = 0; $j < $g_per; $j++) {
	for ($c = 0; $c < $g_cols; $c++) {
		$size = $g_heights[($j + $c * 2) % 6];
		if ((($j + $c) % 3) === 1) {
			$g_cols_data[$c][] = ['size' => $size, 'tone' => $g_tones[($j * 2 + $c) % 3]];
		} else {
			$g_cols_data[$c][] = ['size' => $size, 'img' => $g_files[$cursor % $g_n], 'frame' => $g_frames[$cursor % 2]];
			$cursor++;
		}
	}
}
@endphp

<section class="sx-hero" id="home" data-sx-hero data-interval="{{ config('landing.hero.interval') }}" aria-roledescription="carousel" aria-label="{{ config('landing.hero.aria_label') }}">
	<div class="sx-container sx-hero__grid">

		{{-- ===== LEFT: text slider (only the text changes) ===== --}}
		<div class="sx-hero__slides">
			@foreach ($sx_slides as $i => $sl)
			@php
				$htag  = $i === 0 ? 'h1' : 'h2';
				$link  = $sx_link($sl['link']);
				$link2 = $sx_link($sl['link2']);
				// a slide without its own link still gets its button
				if ($link === '') { $link = $sx_link(config('landing.hero.default_link', '#portfolio')); }
				if ($link === '') { $link = '#portfolio'; }
			@endphp
			<div class="sx-hs{{ $i === 0 ? ' is-active' : '' }}" role="group" aria-roledescription="slide" aria-label="{{ $i + 1 }} of {{ count($sx_slides) }}">
				<div class="sx-hs__content">
					@if (!empty($sl['tag']))<span class="sx-eyebrow">{{ $sl['tag'] }}</span>@endif
					<{{ $htag }}>{!! $sx_rich($sl['title']) !!}</{{ $htag }}>
					@if (!empty($sl['text']))<p>{!! $sx_rich($sl['text']) !!}</p>@endif
					<div class="sx-actions">
						<a href="{{ $link }}" class="sx-btn sx-btn--primary">{{ !empty($sl['btn']) ? $sl['btn'] : config('landing.hero.more_label') }} <x-site.icon name="arrow" /></a>
						@if ($link2 !== '')<a href="{{ $link2 }}" class="sx-btn sx-btn--ghost">{{ $sl['btn2'] }}</a>@endif
					</div>
				</div>
			</div>
			@endforeach
		</div>

		{{-- ===== RIGHT: product gallery (decorative, independent of the text) ===== --}}
		<div class="sx-hero__gallery" aria-hidden="true">
			<div class="sx-gal" style="--cols: {{ $g_cols }}">
				@foreach ($g_cols_data as $c => $tiles)
				<div class="sx-gcol{{ $c % 2 ? ' is-down' : '' }}" style="--dur: {{ $g_durs[$c % count($g_durs)] }}s; --delay: -{{ ($c * 19) % 61 }}s">
					<div class="sx-gcol__track">
						@foreach ([0, 1] as $pass)
							@foreach ($tiles as $t)
								@if (isset($t['img']))
								<div class="sx-tile sx-tile--img sx-tile--{{ $t['size'] }} sx-frame--{{ $t['frame'] }}"><img src="{{ url('/public/' . $g_dir . '/' . rawurlencode($t['img'])) }}" alt="" decoding="async" draggable="false" onerror="console.warn('Hero image not found:', this.src); this.parentNode.classList.add('is-missing'); this.remove();"></div>
								@else
								<div class="sx-tile sx-tile--{{ $t['size'] }} sx-tile--{{ $t['tone'] }}"></div>
								@endif
							@endforeach
						@endforeach
					</div>
				</div>
				@endforeach
			</div>
		</div>

	</div>
</section>