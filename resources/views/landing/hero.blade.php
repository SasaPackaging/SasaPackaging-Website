{{-- ======================= HERO SLIDER =======================
     Needs (from landing/index): $banners, $sx_link, $sx_rich
     Text/buttons: Admin > Banners. Fallback slides + labels: config/landing.php
     Behaviour: public/assets/main/js/sasa-hero.js (data-sx-hero)
     Look: public/assets/main/css/sasa-hero.css
     No dots, arrows or pause button: the slides change on their own. --}}
@php
$sx_slides = [];
foreach ($banners as $row) {
	$sx_slides[] = [
		'image' => !empty($row['image']) ? url('/public/upload/banners/' . $row['image']) : '',
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
		$sx_slides[] = ['image' => ''] + $fb;
	}
}
@endphp

<section class="sx-hero" id="home" data-sx-hero data-interval="{{ config('landing.hero.interval') }}" aria-roledescription="carousel" aria-label="{{ config('landing.hero.aria_label') }}">
	@foreach ($sx_slides as $i => $sl)
	@php
		$has_img = $sl['image'] != '';
		$htag = $i === 0 ? 'h1' : 'h2';
		$link  = $sx_link($sl['link']);
		$link2 = $sx_link($sl['link2']);
		// a slide without its own link still gets its button (set landing.hero.default_link in config to change where it goes)
		if ($link === '') { $link = $sx_link(config('landing.hero.default_link', '#portfolio')); }
		if ($link === '') { $link = '#portfolio'; }
	@endphp
	<div class="sx-slide sx-slide--c{{ ($i % 4) + 1 }}{{ $i === 0 ? ' is-active' : '' }}" role="group" aria-roledescription="slide" aria-label="{{ $i + 1 }} of {{ count($sx_slides) }}">
		<div class="sx-slide__bg{{ $has_img ? '' : ' sx-slide__bg--art' }}"@if ($has_img) style="background-image:url('{{ $sl['image'] }}')"@endif></div>
		@unless ($has_img)
		<div class="sx-slide__art"><x-site.box-art /></div>
		@endunless
		<div class="sx-container sx-slide__inner">
			<div class="sx-slide__content">
				@if (!empty($sl['tag']))<span class="sx-eyebrow">{{ $sl['tag'] }}</span>@endif
				<{{ $htag }}>{!! $sx_rich($sl['title']) !!}</{{ $htag }}>
				@if (!empty($sl['text']))<p>{!! $sx_rich($sl['text']) !!}</p>@endif
				@if ($link !== '' || $link2 !== '')
				<div class="sx-actions">
					@if ($link !== '')<a href="{{ $link }}" class="sx-btn sx-btn--primary">{{ !empty($sl['btn']) ? $sl['btn'] : config('landing.hero.more_label') }} <x-site.icon name="arrow" /></a>@endif
					@if ($link2 !== '')<a href="{{ $link2 }}" class="sx-btn sx-btn--ghost">{{ $sl['btn2'] }}</a>@endif
				</div>
				@endif
			</div>
		</div>
	</div>
	@endforeach
</section>