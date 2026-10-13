<!-- {{-- ======================= ABOUT =======================
     Needs: $data_printer (CMS id 8), $data_cms (CMS id 1, from WelcomeController), $sx_link
     Content: Admin > Pages. Button + video: config/landing.php (about)
     Look: public/assets/main/css/sasa-about.css   Behaviour: public/assets/main/js/sasa-about.js

     VIDEO (config/landing.php > about):
       'video_url'    => a YouTube link (watch / youtu.be / embed / shorts)  OR  a direct .mp4 / .webm link
       'video_poster' => optional image shown until the video starts
       'video_title'  => short description for screen readers
     It starts by itself (muted, looping, no controls) when the visitor scrolls to this section,
     and pauses when it scrolls away. A direct .mp4 gives the cleanest result (no YouTube at all). --}}
@php
$sx_about = count($data_printer) > 0 ? $data_printer[0] : null;
$sx_home  = (!empty($data_cms) && (!empty($data_cms['heading']) || !empty($data_cms['full_contents']))) ? $data_cms : null;

/* ---- video source ---- */
$sx_vurl    = trim((string) config('landing.about.video_url'));
$sx_vtitle  = (string) config('landing.about.video_title');
$sx_vposter = trim((string) config('landing.about.video_poster'));
$sx_yt      = null;
if ($sx_vurl !== '' && preg_match('~(?:youtube(?:-nocookie)?\.com/(?:embed/|watch\?(?:[^#]*&)?v=|shorts/|live/)|youtu\.be/)([A-Za-z0-9_-]{11})~', $sx_vurl, $sx_m)) {
	$sx_yt = $sx_m[1];
}
$sx_file = ($sx_yt === null && $sx_vurl !== '' && preg_match('~\.(mp4|webm|ogv|mov)(\?.*)?$~i', $sx_vurl)) ? $sx_vurl : null;
$sx_has_video = ($sx_yt !== null || $sx_file !== null);
if ($sx_vposter !== '' && !preg_match('~^(https?:)?//~i', $sx_vposter)) { $sx_vposter = url($sx_vposter); }
@endphp

@if ($sx_about || $sx_home)
<section class="sx-section sx-ab" id="about" data-sx-about>
	<div class="sx-container">

		@if ($sx_about)
		<div class="sx-ab__grid{{ $sx_has_video ? '' : ' sx-ab__grid--solo' }}">

			<div class="sx-ab__text">
				@if (!empty($sx_about['tag_line']))<span class="sx-eyebrow sx-ab__rv" style="--d:0s">{{ $sx_about['tag_line'] }}</span>@endif
				<h2 class="sx-title sx-ab__title sx-ab__rv" style="--d:.1s">{!! $sx_about['heading'] !!}</h2>
				<div class="sx-prose sx-ab__prose sx-ab__rv" style="--d:.2s">{!! $sx_about['short_contents'] !!}</div>
				<div class="sx-actions sx-ab__rv" style="--d:.3s">
					<a href="{{ $sx_link(config('landing.about.cta_link')) }}" class="sx-btn sx-btn--primary">{{ config('landing.about.cta_label') }} <x-site.icon name="arrow" /></a>
				</div>
			</div>

			@if ($sx_has_video)
			<div class="sx-ab__media">
				<div class="sx-ab__frame" data-sx-frame role="img" aria-label="{{ $sx_vtitle }}">
					@if ($sx_vposter !== '')
					<img class="sx-ab__poster" src="{{ $sx_vposter }}" alt="" decoding="async">
					@elseif ($sx_yt)
					<img class="sx-ab__poster" src="https://i.ytimg.com/vi/{{ $sx_yt }}/maxresdefault.jpg" alt="" decoding="async" onerror="this.remove()">
					@endif

					@if ($sx_yt)
					<div class="sx-ab__yt" data-sx-yt="{{ $sx_yt }}" data-title="{{ $sx_vtitle }}"></div>
					@else
					<video class="sx-ab__video" data-sx-video muted loop playsinline preload="metadata" aria-hidden="true" tabindex="-1">
						<source src="{{ $sx_file }}" type="video/{{ strtolower(pathinfo(parse_url($sx_file, PHP_URL_PATH), PATHINFO_EXTENSION)) === 'mov' ? 'quicktime' : strtolower(pathinfo(parse_url($sx_file, PHP_URL_PATH), PATHINFO_EXTENSION)) }}">
					</video>
					@endif
					{{-- invisible shield: blocks hover/click so no player UI can ever appear --}}
					<span class="sx-ab__shield" aria-hidden="true"></span>
				</div>
			</div>
			@endif

		</div>
		@endif

		@if ($sx_home)
		<div class="sx-more"@unless ($sx_about) style="margin-top:0;padding-top:0;border-top:0"@endunless>
			<x-site.split-block :tag="(string) $sx_home['tag_line']" :heading="(string) $sx_home['heading']" :html="(string) $sx_home['full_contents']" :img="(string) ($sx_home['banner'] ?? '')" />
		</div>
		@endif

	</div>
</section>
@endif -->

{{-- ======================= ABOUT =======================
     Needs: $data_printer (CMS id 8), $data_cms (CMS id 1, from WelcomeController), $sx_link
     Content: Admin > Pages. Button + video: config/landing.php (about)
     Look: public/assets/main/css/sasa-about.css   Behaviour: public/assets/main/js/sasa-about.js

     VIDEO (config/landing.php > about):
       'video_url'    => a YouTube link (watch / youtu.be / embed / shorts)  OR  a direct .mp4 / .webm link
       'video_poster' => optional image shown until the video starts
       'video_title'  => short description for screen readers
     It starts by itself (muted, looping, no controls) when the visitor scrolls to this section,
     and pauses when it scrolls away. A direct .mp4 gives the cleanest result (no YouTube at all). --}}
@php
$sx_about = count($data_printer) > 0 ? $data_printer[0] : null;
// The CMS id 1 block ("PRINTING SERVICES") now lives in the Services grid, so it is hidden here by default.
// Set 'show_home_block' => true under landing.about in config/landing.php to bring it back in About.
$sx_home  = (config('landing.about.show_home_block', false) && !empty($data_cms) && (!empty($data_cms['heading']) || !empty($data_cms['full_contents']))) ? $data_cms : null;

/* ---- video source ---- */
$sx_vurl    = trim((string) config('landing.about.video_url'));
$sx_vtitle  = (string) config('landing.about.video_title');
$sx_vposter = trim((string) config('landing.about.video_poster'));
$sx_yt      = null;
if ($sx_vurl !== '' && preg_match('~(?:youtube(?:-nocookie)?\.com/(?:embed/|watch\?(?:[^#]*&)?v=|shorts/|live/)|youtu\.be/)([A-Za-z0-9_-]{11})~', $sx_vurl, $sx_m)) {
	$sx_yt = $sx_m[1];
}
$sx_file = ($sx_yt === null && $sx_vurl !== '' && preg_match('~\.(mp4|webm|ogv|mov)(\?.*)?$~i', $sx_vurl)) ? $sx_vurl : null;
$sx_has_video = ($sx_yt !== null || $sx_file !== null);
if ($sx_vposter !== '' && !preg_match('~^(https?:)?//~i', $sx_vposter)) { $sx_vposter = url($sx_vposter); }
@endphp

@if ($sx_about || $sx_home)
<section class="sx-section sx-ab" id="about" data-sx-about>
	<div class="sx-container">

		@if ($sx_about)
		<div class="sx-ab__grid{{ $sx_has_video ? '' : ' sx-ab__grid--solo' }}">

			<div class="sx-ab__text">
				@if (!empty($sx_about['tag_line']))<span class="sx-eyebrow sx-ab__rv" style="--d:0s">{{ $sx_about['tag_line'] }}</span>@endif
				<h2 class="sx-title sx-ab__title sx-ab__rv" style="--d:.1s">{!! $sx_about['heading'] !!}</h2>
				<div class="sx-prose sx-ab__prose sx-ab__rv" style="--d:.2s">{!! $sx_about['short_contents'] !!}</div>
				<div class="sx-actions sx-ab__rv" style="--d:.3s">
					<a href="{{ $sx_link(config('landing.about.cta_link')) }}" class="sx-btn sx-btn--primary">{{ config('landing.about.cta_label') }} <x-site.icon name="arrow" /></a>
				</div>
			</div>

			@if ($sx_has_video)
			<div class="sx-ab__media">
				<div class="sx-ab__frame" data-sx-frame role="img" aria-label="{{ $sx_vtitle }}">
					@if ($sx_vposter !== '')
					<img class="sx-ab__poster" src="{{ $sx_vposter }}" alt="" decoding="async">
					@elseif ($sx_yt)
					<img class="sx-ab__poster" src="https://i.ytimg.com/vi/{{ $sx_yt }}/maxresdefault.jpg" alt="" decoding="async" onerror="this.remove()">
					@endif

					@if ($sx_yt)
					<div class="sx-ab__yt" data-sx-yt="{{ $sx_yt }}" data-title="{{ $sx_vtitle }}"></div>
					@else
					<video class="sx-ab__video" data-sx-video muted loop playsinline preload="metadata" aria-hidden="true" tabindex="-1">
						<source src="{{ $sx_file }}" type="video/{{ strtolower(pathinfo(parse_url($sx_file, PHP_URL_PATH), PATHINFO_EXTENSION)) === 'mov' ? 'quicktime' : strtolower(pathinfo(parse_url($sx_file, PHP_URL_PATH), PATHINFO_EXTENSION)) }}">
					</video>
					@endif
					{{-- invisible shield: blocks hover/click so no player UI can ever appear --}}
					<span class="sx-ab__shield" aria-hidden="true"></span>
				</div>
			</div>
			@endif

		</div>
		@endif

		@if ($sx_home)
		<div class="sx-more"@unless ($sx_about) style="margin-top:0;padding-top:0;border-top:0"@endunless>
			<x-site.split-block :tag="(string) $sx_home['tag_line']" :heading="(string) $sx_home['heading']" :html="(string) $sx_home['full_contents']" :img="(string) ($sx_home['banner'] ?? '')" />
		</div>
		@endif

	</div>
</section>
@endif