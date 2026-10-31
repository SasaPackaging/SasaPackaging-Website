{{-- partial\captcha-script.blade.php - loads Cloudflare's Turnstile script. Include it ONCE, before sasa-forms.js,
     only on pages that have a captcha form. (Cloudflare asks that this script is loaded from their address, never copied.) --}}
@if(config('captcha.enabled') && config('captcha.site_key'))
<script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
@endif
