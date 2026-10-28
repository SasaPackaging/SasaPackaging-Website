# Security + validation update (26 Oct 2026)

Is zip me sirf CHANGED / NEW files hain. Apne project me same folder paths par copy kar dein (overwrite).

## Delete karni hain (purani file)
- `app/Http/Controllers/CommonController.php`  (insecure: kisi bhi file type ka upload public folder me)

## Naye files
- `app/Support/Sanitize.php`, `app/Support/UploadGuard.php`, `app/Http/Middleware/SecurityHeaders.php`
- `database/migrations/2026_10_26_000001_add_is_unpaid_to_tbl_job_applications.php`
- `public/upload/.htaccess`  (uploads folder me PHP kabhi run nahi hoga)
- `.env.production.example`  (live .env ke liye safe settings - sirf reference)

## Commands (Windows CMD, project folder me)
    php artisan migrate
    php artisan config:clear
    php artisan view:clear
    php artisan route:clear

## Live server (GoDaddy) .env me zaroor check karein
    APP_DEBUG=false
    APP_ENV=production
    SESSION_SECURE_COOKIE=true
    (optional) TRUSTED_HOSTS=yourdomain.com,www.yourdomain.com

## Upload se PEHLE zip se hata dein
`storage/logs/laravel.log`, `storage/framework/sessions/*`, `storage/framework/cache/data/*`,
`storage/framework/views/*`, `storage/app/quote-files/*` (customer ki file), `bootstrap/cache/pac*.tmp`
