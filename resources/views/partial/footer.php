<?php
/* ---- Data (unchanged from the original footer) ---- */
$about_us = App\Model\Cms::whereRaw(" id=2 ")->first()->toArray();
$db_pages_footer = App\Model\Cms::whereRaw("status = 'Yes' AND p_id=0 AND  is_footer='Yes'  AND id!=1 ")->orderByRaw('sort_order')->get()->toArray();
/* pages listed in config/site.php > hidden_pages are not shown in Quick Links (nothing is deleted) */
$sx_hidden = (array) config('site.hidden_pages', []);
$db_pages_footer = array_values(array_filter($db_pages_footer, function ($p) use ($sx_hidden) {
	return !in_array($p['slug'], $sx_hidden, true);
}));
$main_path = Config::get('app.url');
$settings = App\Model\Setting::select('key_name', 'key_value')->get()->toArray();
$array_settings = array();
foreach ($settings as $k => $setting) {
	$array_settings[$setting['key_name']] = $setting['key_value'];
}

/* ---- Presentation helpers ---- */
$sx_get = function ($key) use ($array_settings) {
	return isset($array_settings[$key]) ? $array_settings[$key] : '';
};
// Optional: add public/assets/main/images/sasa-logo.png (transparent) and it replaces the placeholder automatically.
$sx_logo = file_exists(base_path('public/assets/main/images/sasa-logo.png')) ? 'sasa-logo.png' : 'foot-logo.svg';
$sx_site_name = $sx_get('SITE_NAME') != '' ? $sx_get('SITE_NAME') : 'SASA Packaging';
$sx_email = $sx_get('CONTACT_EMAIL');
$sx_phone = $sx_get('PHONE');
$sx_phone2 = $sx_get('POBOX');
$sx_address = $sx_get('ADDRESS');

/* Logo: an .svg logo is placed inline so the white "SASA" letters can be recoloured to #84A282
   (the red cube stays red). A .png logo is shown as a normal picture. */
$sx_logo_path = base_path('public/assets/main/images/' . $sx_logo);
$sx_logo_svg = '';
if (substr($sx_logo, -4) === '.svg' && file_exists($sx_logo_path)) {
	$sx_logo_svg = (string) file_get_contents($sx_logo_path);
	$sx_logo_svg = preg_replace('/<\?xml.*?\?>/s', '', $sx_logo_svg);
	$sx_logo_svg = preg_replace('/<!DOCTYPE.*?>/is', '', $sx_logo_svg);
	$sx_logo_svg = trim($sx_logo_svg);
}
?>

<footer class="sx-footer">
	<div class="sx-container sx-footer__grid">

		<!-- Brand -->
		<div class="sx-footer__brand">
			<a class="sx-footer__logo" href="<?php echo URL::to('/'); ?>" aria-label="<?= e($sx_site_name) ?>">
				<?php if ($sx_logo_svg !== '') { ?>
					<?= $sx_logo_svg ?>
				<?php } else { ?>
					<img src="<?= url('/') ?>/public/assets/main/images/<?= $sx_logo ?>" alt="<?= e($sx_site_name) ?>">
				<?php } ?>
			</a>
			<div class="sx-footer__about sx-prose"><?= $about_us['short_contents'] ?></div>
			<ul class="sx-social">
				<?php if ($sx_get('FACEBOOK') != '') { ?>
					<li><a href="<?= e($sx_get('FACEBOOK')) ?>" target="_blank" rel="noopener" aria-label="Facebook"><svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
								<path d="M13.5 21v-7.5H16l.5-3h-3V8.6c0-.9.3-1.6 1.6-1.6h1.5V4.3C16.3 4.2 15.4 4 14.4 4 12.2 4 10.5 5.3 10.5 8v2.5H8v3h2.5V21h3z" />
							</svg></a></li>
				<?php } ?>
				<?php if ($sx_get('INSTAGRAM') != '') { ?>
					<li><a href="<?= e($sx_get('INSTAGRAM')) ?>" target="_blank" rel="noopener" aria-label="Instagram"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
								<rect x="3.5" y="3.5" width="17" height="17" rx="5" />
								<circle cx="12" cy="12" r="4" />
								<circle cx="17.2" cy="6.8" r="1" fill="currentColor" stroke="none" />
							</svg></a></li>
				<?php } ?>
				<?php if ($sx_get('LINKEDIN') != '') { ?>
					<li><a href="<?= e($sx_get('LINKEDIN')) ?>" target="_blank" rel="noopener" aria-label="LinkedIn"><svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
								<path d="M6.5 9H3.7v11h2.8V9zM5.1 4.5a1.6 1.6 0 100 3.2 1.6 1.6 0 000-3.2zM20.3 20h-2.8v-5.4c0-1.4-.5-2.2-1.7-2.2-1.2 0-1.8.9-1.8 2.2V20h-2.8V9h2.7v1.3c.5-.8 1.5-1.5 3-1.5 2.4 0 3.4 1.6 3.4 4.1V20z" />
							</svg></a></li>
				<?php } ?>
				<?php if ($sx_get('YOUTUBE') != '') { ?>
					<li><a href="<?= e($sx_get('YOUTUBE')) ?>" target="_blank" rel="noopener" aria-label="YouTube"><svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
								<path d="M21.6 7.2a2.5 2.5 0 0 0-1.8-1.8C18.2 5 12 5 12 5s-6.2 0-7.8.4A2.5 2.5 0 0 0 2.4 7.2C2 8.8 2 12 2 12s0 3.2.4 4.8a2.5 2.5 0 0 0 1.8 1.8C5.8 19 12 19 12 19s6.2 0 7.8-.4a2.5 2.5 0 0 0 1.8-1.8c.4-1.6.4-4.8.4-4.8s0-3.2-.4-4.8zM10 15V9l5.2 3L10 15z" />
							</svg></a></li>
				<?php } ?>
			</ul>
		</div>

		<!-- Quick links -->
		<div>
			<h3><?= e(config('site.footer.quick_links_title')) ?></h3>
			<ul class="page-list">
				<li><a href="<?php echo URL::to('/'); ?>"><?= e(config('site.footer.home_label')) ?></a></li>
				<?php foreach ($db_pages_footer as $row) { ?>
					<li><a href="<?= $main_path ?>/<?= $row['slug'] ?>.html"><?= e($row['name']) ?></a></li>
				<?php } ?>
				<li><a href="<?= url('/machines.html') ?>"><?= e(config('site.footer.machines_label')) ?></a></li>
			</ul>
		</div>

		<!-- Contact -->
		<div>
			<h3><?= e(config('site.footer.contact_title')) ?></h3>
			<ul class="sx-contact">
				<?php if ($sx_address != '') { ?>
					<li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
							<path d="M12 21s7-6.2 7-11.5A7 7 0 0 0 5 9.5C5 14.8 12 21 12 21z" />
							<circle cx="12" cy="9.5" r="2.5" />
						</svg><span><?= nl2br(e($sx_address)) ?></span></li>
				<?php } ?>
				<?php if ($sx_phone != '' || $sx_phone2 != '') { ?>
					<li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
							<path d="M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2z" />
						</svg>
						<span>
							<?php if ($sx_phone != '') { ?><a href="tel:<?= e($sx_phone) ?>"><?= e($sx_phone) ?></a><?php } ?>
							<?php if ($sx_phone != '' && $sx_phone2 != '') { ?><br><?php } ?>
							<?php if ($sx_phone2 != '') { ?><a href="tel:<?= e($sx_phone2) ?>"><?= e($sx_phone2) ?></a><?php } ?>
						</span>
					</li>
				<?php } ?>
				<?php if ($sx_email != '') { ?>
					<li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
							<rect x="3" y="5" width="18" height="14" rx="2" />
							<path d="m3.5 7 8.5 6 8.5-6" />
						</svg><span><a href="mailto:<?= e($sx_email) ?>"><?= e($sx_email) ?></a></span></li>
				<?php } ?>
			</ul>
		</div>

		<!-- Map -->
		<div class="sx-footer__map">
			<h3><?= e(config('site.footer.map_title')) ?></h3>
			<iframe title="<?= e($sx_site_name) ?>"
				src="<?= e(config('site.footer.map_embed_url')) ?>"
				width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
				referrerpolicy="no-referrer-when-downgrade"></iframe>
		</div>

	</div>

	<div class="sx-footer__bar">
		<div class="sx-container" style="justify-content:center;text-align:center">
			<div>Copyright &copy; <span><?= date('Y') ?></span> <?= e($sx_site_name) ?>. <?= e(config('site.footer.copyright_text')) ?></div>
		</div>
	</div>
</footer>
<!-- End Main Footer -->

<button type="button" class="sx-totop" id="sxToTop" aria-label="<?= e(config('site.footer.back_to_top_label')) ?>"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
		<path d="m6 14 6-6 6 6" />
	</svg></button>

<style>
	/* inline footer logo size */
	.sx-footer__logo svg {
		display: block;
		height: clamp(64px, 6vw, 84px);
		width: auto;
		max-width: 100%;
	}
</style>
<script>
	/* Footer logo: every white part (the word "SASA") becomes #84A282; the red cube is not touched. */
	(function() {
		var svg = document.querySelector('.sx-footer__logo svg');
		if (!svg) {
			return;
		}
		var shapes = svg.querySelectorAll('path, polygon, polyline, rect, circle, ellipse');
		for (var i = 0; i < shapes.length; i++) {
			var f = window.getComputedStyle(shapes[i]).fill;
			var m = f && f.match(/[\d.]+/g);
			if (m && m.length >= 3 && +m[0] >= 235 && +m[1] >= 235 && +m[2] >= 235) {
				shapes[i].style.fill = '#2b332a';
			}
		}
	})();
</script>