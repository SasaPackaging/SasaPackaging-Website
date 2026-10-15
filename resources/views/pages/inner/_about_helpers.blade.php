{{-- pages\inner\_about_helpers.blade.php --}}
{{-- Helpers for the About Us pages (About, CEO, Mission, Vision, Values, Strategy, Certificates, Services detail).
     Images: public/assets/main/images/about-images/{page-slug}.jpg  (+ {page-slug}-2.jpg ...)  ->  else the image uploaded in Admin  ->  else a themed tile. --}}
@include('pages.inner._helpers')
@php
if (!function_exists('sxa_img')) {
	function sxa_img($slug, $db = '')
	{
		$slug = preg_replace('/[^A-Za-z0-9._-]/', '', (string) $slug);
		$base = base_path('public/assets/main/images/about-images/');
		if ($slug !== '') {
			foreach (['webp', 'jpg', 'jpeg', 'png', 'avif'] as $e) {
				if (is_file($base . $slug . '.' . $e)) { return url('/public/assets/main/images/about-images/' . $slug . '.' . $e); }
			}
		}
		$db = trim((string) $db);
		if ($db !== '' && is_file(base_path('public/upload/cms/' . $db))) { return url('/public/upload/cms/' . rawurlencode($db)); }
		return '';
	}
}
if (!function_exists('sxa_extra')) {
	function sxa_extra($slug)
	{
		$slug = preg_replace('/[^A-Za-z0-9._-]/', '', (string) $slug);
		$out = [];
		if ($slug === '') { return $out; }
		$files = glob(base_path('public/assets/main/images/about-images/') . $slug . '-[0-9]*.{webp,jpg,jpeg,png,avif}', GLOB_BRACE) ?: [];
		natsort($files);
		foreach ($files as $f) { $out[] = url('/public/assets/main/images/about-images/' . basename($f)); }
		return $out;
	}
}
if (!function_exists('sxa_plain')) {
	/* Turns CMS HTML into short plain text: strips tags, decodes &amp; etc., trims to $len characters */
	function sxa_plain($html, $len = 200)
	{
		$t = trim(preg_replace('/\s+/', ' ', strip_tags(html_entity_decode((string) $html))));
		return $t === '' ? '' : mb_strimwidth($t, 0, $len, '…');
	}
}
if (!function_exists('sxa_clean')) {
	/* Cleans CMS HTML so it flows as normal full-width paragraphs:
	   removes old layout attributes (style, class, width, align ...) and, when the text has no real
	   paragraphs, turns line breaks into <p> paragraphs. Used only on the About pages. */
	function sxa_clean($html)
	{
		$h = (string) $html;
		if (trim($h) === '') { return ''; }
		$h = preg_replace('/<(script|style)\b[^>]*>.*?<\/\1>/is', '', $h);
		$h = preg_replace_callback('/<([a-z][a-z0-9]*)\b([^>]*)>/i', function ($m) {
			$attrs = preg_replace('/\s+(style|class|width|height|align|valign|bgcolor|id)\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)/i', '', $m[2]);
			return '<' . $m[1] . $attrs . '>';
		}, $h);
		$h = str_replace(['&nbsp;', "\xC2\xA0"], ' ', $h);
		/* a link whose text is accidentally written twice ("More About UsMore About Us") -> show it once */
		$h = preg_replace_callback('~(<a\b[^>]*>)(.*?)(</a>)~is', function ($m) {
			$t = trim(preg_replace('/\s+/', ' ', strip_tags($m[2])));
			if ($t !== '' && preg_match('/^(.+?)\s*\1$/su', $t, $mm)) {
				return $m[1] . htmlspecialchars($mm[1], ENT_QUOTES, 'UTF-8') . $m[3];
			}
			return $m[0];
		}, $h);
		if (!preg_match('/<(p|ul|ol|table|h[1-6]|blockquote)\b/i', $h)) {
			$h = preg_replace('/<\/?div\b[^>]*>/i', '<br>', $h);
			$parts = preg_split('/(?:\s*<br\s*\/?>\s*)+/i', $h);
			$keep = [];
			foreach ($parts as $p) {
				$p = trim($p);
				if ($p !== '') { $keep[] = $p; }
			}
			$h = $keep ? '<p>' . implode('</p><p>', $keep) . '</p>' : '';
		}
		$h = preg_replace('~<p[^>]*>(\s|<br\s*/?>)*</p>~i', '', $h);
		return $h;
	}
}
if (!function_exists('sxa_kind')) {
	function sxa_kind($slug, $name = '')
	{
		$s = strtolower($slug . ' ' . $name);
		foreach (['mission' => 'mission', 'vision' => 'vision', 'value' => 'values', 'strateg' => 'strategy', 'certif' => 'certificates', 'ceo' => 'ceo', 'about' => 'about'] as $k => $v) {
			if (strpos($s, $k) !== false) { return $v; }
		}
		return 'default';
	}
}
if (!function_exists('sxa_icon')) {
	function sxa_icon($kind)
	{
		$p = [
			'mission'      => '<circle cx="12" cy="12" r="9"/><circle cx="12" cy="12" r="5"/><circle cx="12" cy="12" r="1.3"/>',
			'vision'       => '<path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7S2 12 2 12z"/><circle cx="12" cy="12" r="3"/>',
			'values'       => '<path d="M12 20s-7-4.4-7-10a4 4 0 0 1 7-2.6A4 4 0 0 1 19 10c0 5.6-7 10-7 10z"/>',
			'strategy'     => '<circle cx="12" cy="12" r="9"/><path d="m15.5 8.5-2 5-5 2 2-5z"/>',
			'certificates' => '<path d="M12 3 4.5 6v5.5c0 4.4 3 7.7 7.5 9.5 4.5-1.8 7.5-5.1 7.5-9.5V6z"/><path d="m8.8 12 2.4 2.4 4.2-4.6"/>',
			'ceo'          => '<circle cx="12" cy="8" r="3.6"/><path d="M4.5 20c.6-4 3.6-6 7.5-6s6.9 2 7.5 6"/>',
			'about'        => '<path d="M3 21V9l9-6 9 6v12z"/><path d="M9 21v-7h6v7"/>',
			'default'      => '<path d="M12 3l2.4 5.6L20 9l-4.3 3.9L17 19l-5-3-5 3 1.3-6.1L4 9l5.6-.4z"/>',
		];
		$d = isset($p[$kind]) ? $p[$kind] : $p['default'];
		return '<svg class="sxa-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . $d . '</svg>';
	}
}
if (!function_exists('sxa_nav')) {
	/* siblings of a CMS page (the other pages in the same menu) + previous / next */
	function sxa_nav($id)
	{
		$out = ['parent' => '', 'sibs' => [], 'prev' => null, 'next' => null, 'slug' => '', 'row' => []];
		$row = $id ? App\Model\Cms::find($id) : null;
		if (!$row) { return $out; }
		$row = $row->toArray();
		$out['row'] = $row; $out['slug'] = $row['slug'] ?? '';
		$pid = (int) ($row['p_id'] ?? 0) ?: (int) $row['id'];
		$q = "status = 'Yes' AND p_id = " . $pid . " AND id != 1";
		if ((int) ($row['is_header'] ?? 0) === 1) { $q .= " AND is_header = 1"; }
		$out['sibs'] = App\Model\Cms::whereRaw($q)->orderByRaw('sort_order')->get()->toArray();
		if ((int) ($row['p_id'] ?? 0)) { $par = App\Model\Cms::find($row['p_id']); $out['parent'] = $par ? $par['name'] : ''; } else { $out['parent'] = $row['name']; }
		foreach ($out['sibs'] as $k => $s) {
			if ((int) $s['id'] === (int) $id) {
				$out['prev'] = $out['sibs'][$k - 1] ?? null;
				$out['next'] = $out['sibs'][$k + 1] ?? null;
			}
		}
		return $out;
	}
}
if (!function_exists('sxa_nice')) {
	function sxa_nice($key)
	{
		$w = [];
		foreach (preg_split('/[-_\s]+/', (string) $key) as $t) {
			if ($t === '') { continue; }
			$w[] = (preg_match('/\d/', $t) || strlen($t) <= 4) ? strtoupper($t) : ucfirst($t);
		}
		return implode(' ', $w);
	}
}
if (!function_exists('sxa_certs')) {
	/* 1) images in about-images (certificate-*.ext)  2) entries of config/about.php  3) the landing page list */
	function sxa_certs()
	{
		$dir = base_path('public/assets/main/images/about-images/');
		$web = url('/public/assets/main/images/about-images/');
		$meta = (array) config('about.certificates', []);
		$items = [];
		$files = glob($dir . 'certificate-*.{webp,jpg,jpeg,png,svg,avif}', GLOB_BRACE) ?: [];
		natsort($files);
		foreach ($files as $f) {
			$base = pathinfo($f, PATHINFO_FILENAME);
			if (substr($base, -4) === '-doc') { continue; }
			$key = substr($base, strlen('certificate-'));
			$docs = glob($dir . $base . '-doc.{webp,jpg,jpeg,png,avif}', GLOB_BRACE) ?: [];
			$m = $meta[$key] ?? [];
			$name = $m['name'] ?? sxa_nice($key);
			$items[] = ['name' => $name, 'short' => $m['short'] ?? $name, 'image' => $web . '/' . basename($f),
				'doc' => $docs ? $web . '/' . basename($docs[0]) : '', 'issuer' => $m['issuer'] ?? '', 'year' => $m['year'] ?? '', 'note' => $m['note'] ?? ''];
		}
		if (!$items) {
			foreach ($meta as $key => $m) {
				$name = $m['name'] ?? sxa_nice($key);
				$items[] = ['name' => $name, 'short' => $m['short'] ?? $name, 'image' => '', 'doc' => '', 'issuer' => $m['issuer'] ?? '', 'year' => $m['year'] ?? '', 'note' => $m['note'] ?? ''];
			}
		}
		if (!$items) {
			foreach ((array) config('landing.certifications.items', []) as $c) {
				$items[] = ['name' => $c['name'] ?? '', 'short' => $c['short'] ?? ($c['name'] ?? ''), 'image' => '', 'doc' => '', 'issuer' => '', 'year' => '', 'note' => $c['note'] ?? ''];
			}
		}
		return $items;
	}
}
@endphp