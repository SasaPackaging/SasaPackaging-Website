# SASA Admin panel — Step 1 & 2 (Contact us + Get a Quote)

Admin URL: `yoursite.com/admin`  (prefix can be changed with `ADMIN_PREFIX` in `.env`)

## 1. First-time setup (Windows CMD, inside the project folder)

```
composer dump-autoload
php artisan migrate --path=database/migrations/admin
php artisan admin:create
php artisan config:clear
php artisan view:clear
```

* `migrate --path=...admin` runs ONLY the new admin tables (admins, contact_messages,
  quote_requests, inquiry_replies). Existing tables are never touched; every migration
  skips a table that already exists.
* `admin:create` asks for name, email and password (nothing is stored in code).
  Run it again with the same email to reset a password.

## 2. .env keys used

```
MAIL_MAILER=smtp
MAIL_HOST=...            (GoDaddy / your SMTP host)
MAIL_PORT=587
MAIL_USERNAME=...
MAIL_PASSWORD=...
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="SASA Packaging"

INBOX_NOTIFY_TO=         (optional backup address; the main one is CONTACT_EMAIL in tbl_settings)
ADMIN_TIMEZONE=Asia/Karachi
```
For local testing use `MAIL_MAILER=log` — emails are written to `storage/logs/laravel.log`.

## 3. Folder structure (admin is kept separate from the website)

```
app/
  Http/Controllers/Admin/        AuthController, DashboardController, InboxController (shared),
                                 ContactMessageController, QuoteRequestController
  Http/Controllers/Site/         ContactController, QuoteController   (public forms)
  Http/Middleware/Admin/         Authenticate, RedirectIfAuthenticated
  Http/Requests/Admin/           LoginRequest, SendReplyRequest, UpdateInboxStatusRequest
  Http/Requests/Site/            StoreContactRequest, StoreQuoteRequest (validation)
  Models/                        Admin, ContactMessage, QuoteRequest, InquiryReply
  Models/Concerns/InboxItem.php  status / search / replies shared by both inboxes
  Services/Inbox/                InquiryNotifier (email to company), ReplyService
  Services/Quote/                QuoteOptionsService, QuoteSubmissionService
  Mail/Inbox/                    ContactMessageReceived, QuoteRequestReceived, InquiryReplyMail
  View/Composers/                Admin/SidebarComposer, QuoteFormComposer
  Support/                       SiteSettings, AdminAsset
  Console/Commands/CreateAdmin.php
config/admin.php                 prefix, menu, statuses, per-page, timezone, notification settings
config/quote.php                 every quote drop-down list, file rules, product/service sources
database/migrations/admin/       only admin migrations
resources/lang/en/inbox.php      website success/error texts + email wording
resources/views/admin/           layouts, partials, auth, dashboard, inbox partials, contact-messages, quote-requests
resources/views/components/admin/  reusable <x-admin.*> components
resources/views/emails/inbox/    notification + reply email templates
routes/admin.php                 all admin routes (one block per feature)
public/assets/admin/css/         tokens, base, layout, components, auth, dashboard, inbox
public/assets/admin/js/admin.js
```

## 4. Reusable components (`resources/views/components/admin`)

`<x-admin.page-header>` `<x-admin.card>` `<x-admin.button>` `<x-admin.badge>` `<x-admin.status-badge>`
`<x-admin.stat-card>` `<x-admin.alert>` `<x-admin.empty-state>` `<x-admin.input>` `<x-admin.textarea>`
`<x-admin.detail-row>` `<x-admin.datetime>` `<x-admin.delete-form>` `<x-admin.icon>`
Pagination: `{{ $items->links('admin.partials.pagination') }}`

## 5. What changed in the existing website (nothing visual)

* `routes/web.php` — `/common/contact_process` and `/send_quote` now point to `Site\ContactController` / `Site\QuoteController`
  (same URLs, same field names, same JSON reply, so the JavaScript is unchanged). Throttled to 10 requests/minute.
* `CommonController` — the two old methods (inline HTML mail) were removed. `save_career` is untouched.
* `pages/quote.blade.php` — drop-down options now loop over `config/quote.php` (same options, same look), and two
  optional selects were added: **Product** and **Service** (shown only when the table has active rows).
* `config/auth.php`, `Http/Kernel.php`, both providers — admin guard, middleware, routes, view composers.

## 6. How the next steps plug in

1. migration in `database/migrations/admin/`, model in `app/Models/`
2. controller in `app/Http/Controllers/Admin/`, form requests in `app/Http/Requests/Admin/`
3. a route block in `routes/admin.php`
4. views in `resources/views/admin/<feature>/`
The sidebar link already exists in `config/admin.php` and appears automatically once the route exists.

## Roles (who can open what)
- `super` (IT support): everything, incl. **Admin users** page.
- `staff` (person who answers quotes/contacts): only Contact + Quote inbox.
- Roles/permissions live in `config/admin.php` -> `roles`. Add admins at `/admin/admin-users` or `php artisan admin:create --role=staff`.

## Website content (Landing / footer / inner pages)
- Wording of the home page + footer is defined in `config/landing.php` and `config/site.php`. Edits made in the admin are saved in `site_contents` and override those files (no Blade changes). "Restore original wording" deletes the edit.
- What is editable (fields, sections, tables) is declared in `config/admin_content.php`. To make one more text editable, add one line there.
- Contact details / social links are saved in `tbl_settings`. Home slider = `tbl_banners`, Mission & values panels = `tbl_services`, Inner pages = `tbl_cms`.
