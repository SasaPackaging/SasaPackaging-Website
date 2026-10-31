{{-- partial\captcha.blade.php - the Cloudflare Turnstile box shown inside a public form.
     Use INSIDE the <form>, just above the submit button:   @include('partial.captcha', ['action' => 'contact'])
     "action" must be the same word used in routes/web.php  (captcha:contact / captcha:quote).
     Draws nothing when the captcha is switched off or no site key is set. Settings: config/captcha.php --}}
@if(config('captcha.enabled') && config('captcha.site_key'))
<div class="sxf-captcha">
	<div class="cf-turnstile" data-sxf-captcha
		data-sitekey="{{ config('captcha.site_key') }}"
		data-action="{{ $action ?? '' }}"
		data-theme="{{ config('captcha.theme', 'light') }}"
		data-appearance="{{ config('captcha.appearance', 'always') }}"
		data-size="flexible"
		data-language="auto"></div>
</div>
@endif
