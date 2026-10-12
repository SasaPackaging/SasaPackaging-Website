SASA PACKAGING - OLD SITE, LOCAL RUN GUIDE (fixed copy)
=======================================================

What was fixed
- Rebuilt missing: artisan, composer.json
- Laravel 8 crashed on PHP 8.1/8.2/8.3/8.4 because it turns every "deprecated" notice into an
  exception. Fix: app/Bootstrap/HandleExceptions.php (+ used in app/Http/Kernel.php and
  app/Console/Kernel.php).
- Carbon (date library) PHP 8.2 bug patched in vendor/nesbot/carbon/.../Traits/Creator.php
- routes/web.php no longer crashes under artisan (REQUEST_URI guard)
- vendor/ autoload regenerated. Cleaned storage/logs and sessions.
- NOT included (on purpose): .env, database dump, public/ (CSS/JS/images/uploads)

Steps
1) Copy YOUR OWN .env from your first zip into this folder
   (or copy .env.example to .env and run:
    C:\xampp\php\php.exe -r "echo 'base64:'.base64_encode(random_bytes(32));"
    and paste into APP_KEY=). Set DB_DATABASE / DB_USERNAME / DB_PASSWORD to your LOCAL MySQL.
2) Import database (cmd, not PowerShell):
    mysql -u root -p -e "CREATE DATABASE sasa_packaging CHARACTER SET utf8mb4;"
    mysql -u root -p sasa_packaging < localhost.sql
3) Put back the public/ folder from your ORIGINAL GoDaddy zip into this folder
   (public/assets has the site's CSS/JS; public/upload has uploaded images).
   Without it the pages open but look unstyled and images are missing.
4) Run:
    C:\xampp\php\php.exe -S 127.0.0.1:8000 local-router.php
   Open http://127.0.0.1:8000   and   http://127.0.0.1:8000/admin
5) If something fails, send the last lines of storage\logs\laravel.log

Do not upload local-router.php, README-LOCAL.txt to GoDaddy.


UI styling updates
------------------
- Public styles: public/assets/main/css/sasa-ui.css
- Legacy CSS entry files: public/assets/main/css/bootstrap.css, style.css, responsive.css
- Admin fallback styles: public/assets/admin/css/custom.css
- Local SVG logo placeholders: public/assets/main/images/
- Tailwind CLI configuration: package.json, tailwind.config.js, resources/css/app.css

Optional Tailwind build (requires internet for the first dependency install):
    npm install
    npm run build:css

The existing legacy pages use many non-Tailwind CSS class names, so sasa-ui.css supplies the
immediate styling foundation for those templates. New UI components can use Tailwind utility
classes after building the Tailwind stylesheet. Do not deploy this local development copy
directly to production without reviewing configuration, assets, and database settings.
