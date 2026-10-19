# Admin removed (clean base)

Removed: app/Http/Controllers/Admin, AdminAuth middleware (+ Kernel entry), Admins model,
resources/views/admin, public/assets/admin, the /admin route group, old notes (*.txt).

Kept untouched: all landing + inner page views, config/landing.php, public/assets/main,
all app/Model/* (frontend reads data through them), BaseController, CommonController, WelcomeController.

Known leftovers (not caused by removal):
- WelcomeController imports App\Model\Features, which does not exist.
- Frontend is data-driven from old tables (cms, services, members, banner, category, products, projects, setting, quote).
  The new admin must keep these table/column names or the frontend must be updated together with it.
- node_modules removed from zip: run `npm install` if you need to rebuild Tailwind.
