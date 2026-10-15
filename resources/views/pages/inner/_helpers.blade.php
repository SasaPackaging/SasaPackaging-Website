<!-- pages\ineer\_helper.blade.php -->
{{-- Shared helpers for the redesigned inner pages (Products & Services, Category, Product detail).
     Image order for every tile:
       1) public/assets/main/images/ProductImages/{slug}.webp|jpg|jpeg|png   (your curated images)
       2) the image uploaded from Admin  (public/upload/products|category/...)
       3) a themed colour tile (sage / blush) with a box icon - never a broken picture
     Gallery extras on the product page: ProductImages/{slug}-2.jpg, {slug}-3.jpg ...  --}}
@php
if (!function_exists('sxi_img')) {
	function sxi_img($slug, $db = '', $dir = 'products')
	{
		$slug = preg_replace('/[^A-Za-z0-9._-]/', '', (string) $slug);
		$base = base_path('public/assets/main/images/ProductImages/');
		if ($slug !== '') {
			foreach (['webp', 'jpg', 'jpeg', 'png', 'avif'] as $e) {
				if (is_file($base . $slug . '.' . $e)) {
					return url('/public/assets/main/images/ProductImages/' . $slug . '.' . $e);
				}
			}
		}
		$db = trim((string) $db);
		if ($db !== '' && is_file(base_path('public/upload/' . $dir . '/' . $db))) {
			return url('/public/upload/' . $dir . '/' . rawurlencode($db));
		}
		return '';
	}
}
if (!function_exists('sxi_extra_imgs')) {
	function sxi_extra_imgs($slug)
	{
		$slug = preg_replace('/[^A-Za-z0-9._-]/', '', (string) $slug);
		$out = [];
		if ($slug === '') { return $out; }
		$files = glob(base_path('public/assets/main/images/ProductImages/') . $slug . '-*.{webp,jpg,jpeg,png,avif}', GLOB_BRACE) ?: [];
		natsort($files);
		foreach ($files as $f) {
			$out[] = url('/public/assets/main/images/ProductImages/' . basename($f));
		}
		return $out;
	}
}
if (!function_exists('sxi_icon')) {
	function sxi_icon()
	{
		return '<svg class="sxi-ph__icon" viewBox="0 0 64 64" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round" stroke-linecap="round" aria-hidden="true"><path d="M32 6 56 18v28L32 58 8 46V18z"/><path d="M8 18l24 12 24-12M32 30v28"/><path d="m20 12 24 12" opacity=".55"/></svg>';
	}
}
if (!function_exists('sxi_plain')) {
	function sxi_plain($html, $len = 200)
	{
		$t = trim(preg_replace('/\s+/', ' ', strip_tags(html_entity_decode((string) $html))));
		return $t === '' ? '' : mb_strimwidth($t, 0, $len, '…');
	}
}
@endphp
