<?php
/* ---- Data (unchanged from the original header: DB-driven navigation + settings) ---- */
$pages = App\Model\Cms::whereRaw("status = 'Yes' AND is_header=1 AND p_id = 0 AND id!=1 ")->orderByRaw('sort_order')->get()->toArray();
$about_home = App\Model\Cms::whereRaw(" id=1 ")->first()->toArray();
$home_active = '';
$slug = '';
if (count(Request::segments()) == 0) {
	$home_active = 'active';
} else {
	$array_pages = Request::segments();
	$slug = str_replace('.html', '', $array_pages[0]);
}
$settings = App\Model\Setting::select('key_name', 'key_value')->get()->toArray();
$array_settings = array();
foreach ($settings as $k => $setting) {
	$array_settings[$setting['key_name']] = $setting['key_value'];
}
$main_path = Config::get('app.url');
$is_mobile = false;

/* ---- Presentation helpers ---- */
$sx_get = function ($key) use ($array_settings) {
	return isset($array_settings[$key]) ? $array_settings[$key] : '';
};
// Drop the processed, transparent logo at public/assets/main/images/sasa-logo.png and it is used automatically.
$sx_logo = file_exists(base_path('public/assets/main/images/sasa-logo.png')) ? 'sasa-logo.png' : 'logo.svg';
$sx_site_name = $sx_get('SITE_NAME') != '' ? $sx_get('SITE_NAME') : 'SASA Packaging';
$sx_phone = $sx_get('PHONE');
?>
<input type="hidden" name="_token" id="_token" value="<?= csrf_token() ?>">

<header class="sx-header" id="sxHeader">
	<div class="sx-container sx-header__bar">

		<a class="sx-logo" href="<?php echo URL::to('/'); ?>">
			<img src="<?= url('/') ?>/public/assets/main/images/<?= $sx_logo ?>" alt="<?= e($sx_site_name) ?>">
		</a>

		<nav class="sx-nav" id="sxNav" aria-label="Primary">
			<ul class="sx-nav__list">
				<li class="<?= $home_active != '' ? 'is-active' : '' ?>"><a href="<?php echo URL::to('/'); ?>" <?= $home_active != '' ? ' aria-current="page"' : '' ?>>Home</a></li>

				<?php foreach ($pages as $row_pages) {
					$mega = false;
					if ($row_pages['slug'] == 'products-and-services') {
						$mega = true;
						$db_pages_child = App\Model\Category::whereRaw("status = 'Yes' AND pid = 0")->orderByRaw('sort_order')->get()->toArray();
					} else {
						$db_pages_child = App\Model\Cms::whereRaw("status = 'Yes' AND is_header=1 AND p_id = " . $row_pages['id'] . " AND id!=1 ")->orderByRaw('sort_order')->get()->toArray();
					}
					$is_active = ($row_pages['slug'] == $slug);
					if ($row_pages['slug'] == 'our-sectors') {
						$main_slug = 'javascript:void(0)';
					} else {
						$main_slug = $main_path . '/' . $row_pages['slug'] . '.html';
					}
					$has_sub = count($db_pages_child) > 0;
					$li_class = trim(($has_sub ? 'has-sub ' : '') . ($mega ? 'is-mega ' : '') . ($is_active ? 'is-active' : ''));
				?>
					<li class="<?= $li_class ?>">
						<a href="<?= $main_slug ?>" <?= $has_sub ? ' aria-haspopup="true"' : '' ?><?= $is_active ? ' aria-current="page"' : '' ?>>
							<?= e($row_pages['name']) ?>
							<?php if ($has_sub) { ?><svg class="sx-caret" viewBox="0 0 12 12" aria-hidden="true">
									<path d="M2.5 4.5 6 8l3.5-3.5" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
								</svg><?php } ?>
						</a>
						<?php if ($has_sub) { ?>
							<button type="button" class="sx-sub-toggle" aria-expanded="false" aria-label="Show <?= e($row_pages['name']) ?> submenu"><svg viewBox="0 0 12 12" aria-hidden="true">
									<path d="M2.5 4.5 6 8l3.5-3.5" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
								</svg></button>

							<?php if ($mega) { ?>
								<ul class="sx-sub sx-mega">
									<?php foreach ($db_pages_child as $row_pages_child) {
										$db_sub_child = App\Model\Products::whereRaw("status = 'Yes' AND category_id = " . $row_pages_child['id'] . " ")->orderByRaw('sort_order')->get()->toArray();
									?>
										<li class="sx-mega__col">
											<h3><a href="<?= $main_path . '/' . $row_pages_child['slug'] . '.html' ?>"><?= e($row_pages_child['name']) ?></a></h3>
											<?php if (count($db_sub_child) > 0) { ?>
												<ul>
													<?php foreach ($db_sub_child as $row_sub_child) { ?>
														<li><a href="<?= $main_path . '/' . $row_sub_child['slug'] . '.html' ?>"><?= e($row_sub_child['name']) ?></a></li>
													<?php } ?>
												</ul>
											<?php } ?>
										</li>
									<?php } ?>
								</ul>
							<?php } else { ?>
								<ul class="sx-sub">
									<?php foreach ($db_pages_child as $row_pages_child) { ?>
										<li><a href="<?= $main_path . '/' . $row_pages_child['slug'] . '.html' ?>"><?= e($row_pages_child['name']) ?></a></li>
									<?php } ?>
								</ul>
							<?php } ?>
						<?php } ?>
					</li>
				<?php } ?>

				<li class="sasa-machines-nav<?= $slug == 'machines' ? ' is-active' : '' ?>"><a href="<?= url('/machines.html') ?>" <?= $slug == 'machines' ? ' aria-current="page"' : '' ?>>Machines</a></li>
			</ul>

			<!-- Mobile-only extras (search + quote + phone) -->
			<div class="sx-nav__extra">
				<form class="sx-search" method="get" action="<?= url('/') ?>/search.html" role="search">
					<input type="search" name="s" value="" placeholder="Search products" aria-label="Search products" required>
					<button type="submit">Search</button>
				</form>
				<a href="<?= url('/') ?>/get-quote.html" class="sx-btn sx-btn--primary">Get a Quote</a>
				<?php if ($sx_phone != '') { ?>
					<a class="sx-phone" href="tel:<?= e($sx_phone) ?>"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
							<path d="M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2z" />
						</svg><?= e($sx_phone) ?></a>
				<?php } ?>
			</div>
		</nav>

		<div class="sx-header__actions">
			<?php if ($sx_phone != '') { ?>
				<a class="sx-phone" href="tel:<?= e($sx_phone) ?>"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
						<path d="M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2z" />
					</svg><?= e($sx_phone) ?></a>
			<?php } ?>
			<a href="<?= url('/') ?>/get-quote.html" class="sx-btn sx-btn--primary sx-btn--sm">Get a Quote</a>
			<button type="button" class="sx-burger" aria-expanded="false" aria-controls="sxNav" aria-label="Open menu"><span></span><span></span><span></span></button>
		</div>

	</div>
</header>
<!-- End Main Header -->