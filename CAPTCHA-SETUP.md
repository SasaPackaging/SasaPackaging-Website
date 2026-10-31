# Captcha for Contact + Get a Quote forms (Cloudflare Turnstile)

This zip contains only the CHANGED / NEW files. Copy them into your project at the same folder paths (overwrite).
They were made from the project zip you sent on 31 Oct 2026. If you changed any of the "changed" files since then, tell me first so we merge instead of overwrite.

## What the visitor sees
A small "Verify you are human" box above the Send button. Most people just see a green tick after a second. No pictures to click.

## What happens behind the scenes
1. The box gives the browser a one-time token.
2. The form is sent with that token.
3. The SERVER asks Cloudflare "is this token real, fresh, from our site and made for this form?" BEFORE anything else runs (before field checks, file scanning, database, email).
4. Fail = the form is refused with a friendly message. Pass = your existing code runs exactly as before.

Your old protections all stay: hidden spam box, 10-per-minute limit, validation, file checks.

## Files

New:
- `config/captcha.php`
- `app/Http/Middleware/VerifyCaptcha.php`
- `app/Services/Captcha/TurnstileVerifier.php`
- `app/Services/Captcha/CaptchaResult.php`
- `resources/views/partial/captcha.blade.php`
- `resources/views/partial/captcha-script.blade.php`

Changed:
- `app/Http/Kernel.php` (registers the `captcha` middleware name)
- `routes/web.php` (the two form routes now use it)
- `resources/lang/en/inbox.php` (3 new messages)
- `resources/views/pages/contact.blade.php`, `resources/views/pages/quote.blade.php`
- `public/assets/main/js/sasa-forms.js` (waits for the check, resets it after each send)
- `public/assets/main/css/sasa-inner-forms.css` (spacing for the box)

## Step 1 - Get your keys (free, 3 minutes)
1. Sign in at https://dash.cloudflare.com (a free account is enough; your domain does NOT need to be on Cloudflare).
2. Left menu: **Turnstile** > **Add widget**.
3. Widget name: `SASA website`. Hostnames: add your live domain (for example `yourdomain.com`, and `www.yourdomain.com` if used). Widget mode: **Managed**.
4. Create. You get two keys:
   - **Site key** (public)
   - **Secret key** (private: never send it by chat/email/screenshot, never commit it)

## Step 2 - Add the keys to `.env`
Add these lines. Do this BEFORE uploading the new code to the live server, otherwise the forms will refuse everyone (by design, see "Safety").

```
TURNSTILE_SITE_KEY=your_real_site_key
TURNSTILE_SECRET_KEY=your_real_secret_key
```

Optional lines (the defaults are the recommended values, you can leave these out):

```
CAPTCHA_ENABLED=true
CAPTCHA_FAIL_OPEN=false
TURNSTILE_THEME=light
TURNSTILE_APPEARANCE=always
```

For your own computer (local testing) use Cloudflare's official test keys instead (they need no account and work on localhost):

```
TURNSTILE_SITE_KEY=1x00000000000000000000AA
TURNSTILE_SECRET_KEY=1x0000000000000000000000000000000AA
```

IMPORTANT: never use these test keys on the live site. They approve everybody. The code detects this and refuses to run on `APP_ENV=production`, and writes a CRITICAL line to the log.

## Step 3 - Clear caches (Windows CMD, in the project folder)

```
php artisan config:clear
php artisan view:clear
php artisan route:clear
```

On the live server do the same (or delete the files inside `bootstrap/cache/` and `storage/framework/views/`). If you ever run `php artisan config:cache`, run it again after changing `.env`.

## Step 4 - Test (5 minutes, locally with the test keys)

| # | Do this | You should see |
|---|---------|----------------|
| 1 | Open Contact page. Wait 1-2 seconds. | The box appears above the button and turns to a green tick. |
| 2 | Fill the form, send. | Normal success message. A new row in the admin inbox. The box resets for the next try. |
| 3 | Click send BEFORE the tick appears. | "Please wait for the security check to finish..." and nothing is sent. |
| 4 | Same for the Quote page (with a file). | Normal success + reference number. |
| 5 | Server-side rejection test: keep site key `1x00000000000000000000AA` but set the secret key to the always-fail one: `2x0000000000000000000000000000000AA`. Run `php artisan config:clear`. Send the form. | "The security check could not be verified. Please try again." and NOTHING is saved. |
| 6 | Put the good test secret key back (`1x0000000000000000000000000000000AA`). Open the page, then in the browser Developer Tools > Network, block `challenges.cloudflare.com`, reload, send. | "The security check could not load. Please refresh the page, or turn off any content blocker..." |
| 7 | Rename the secret key in `.env` to nothing (empty). Send. | "We could not run the security check right now..." and a CRITICAL line in `storage/logs/laravel.log`. |

After going live with the REAL keys, repeat tests 1, 2 and 4 on the live site, then check `storage/logs/laravel.log` has no `Captcha:` warnings.

## Safety design (why it is built this way)
- The secret key lives only in `.env`. It is never in the page, never in logs, never in git (`.env` is already in your `.gitignore`).
- The check is on the server. Hiding the button or editing the JavaScript does not bypass it.
- A token works once and expires in 5 minutes (Cloudflare enforces it), so recorded tokens cannot be replayed.
- A token must come from the right form (`contact` vs `quote`) and from your site (the host in `APP_URL`, with or without `www.`).
- The throttle (10/min) runs BEFORE the captcha check, so a flood cannot become a flood of calls to Cloudflare.
- If Cloudflare cannot be reached or the keys are wrong, the form is REFUSED with a calm message (`CAPTCHA_FAIL_OPEN=false`). A broken setup is loud and safe instead of silently unprotected. If you prefer never losing a lead during an outage, set `CAPTCHA_FAIL_OPEN=true` (then bots can pass during an outage).

## Troubleshooting (look in `storage/logs/laravel.log`)

| Message contains | Meaning | Fix |
|------------------|---------|-----|
| `are not set in .env` | Keys missing | Step 2, then `php artisan config:clear` |
| `rejected our secret key` | Secret key wrong / typo / belongs to another widget | Copy it again from the Cloudflare dashboard |
| `TEST keys are set on a production site` | Test keys on live | Put the real keys |
| `token was created on "x" but only these hosts are accepted` | `APP_URL` in `.env` differs from the real domain | Fix `APP_URL`, or add the domain to `CAPTCHA_ALLOWED_HOSTNAMES=other.com` |
| `could not reach Cloudflare` | Server cannot make outgoing HTTPS calls | Ask the host (GoDaddy) to allow outgoing HTTPS / check the PHP curl + CA certificates |
| Box never appears on the live site | Wrong site key, or the domain is missing in the widget's Hostnames | Fix in Cloudflare dashboard > Turnstile > your widget |

## If you add a Content-Security-Policy later
Allow `https://challenges.cloudflare.com` in `script-src`, `frame-src` and `connect-src`. Today the site has no CSP, so nothing is needed.

## Not covered by this change (same pattern fits, one line each)
- Careers form (`/careers/apply`, accepts CV uploads) and the admin login are also public. Add `'captcha:careers'` / `'captcha:admin-login'` to their route middleware and include the partial in their forms.
