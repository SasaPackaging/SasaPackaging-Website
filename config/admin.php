<?php

/*
|--------------------------------------------------------------------------
| Admin panel settings
|--------------------------------------------------------------------------
| Everything the admin panel needs to know lives here (not inside the code):
| URL prefix, menu, inbox statuses, page size, date format and where emails go.
| After editing run:  php artisan config:clear
*/

return [

    // The admin panel opens at  yoursite.com/{prefix}
    'prefix' => env('ADMIN_PREFIX', 'admin'),

    // Folder (inside the website root) that holds the admin CSS / JS.
    'assets_path' => 'public/assets/admin',

    // Name shown in the admin header and login page. Empty = use the site name from config/site.php.
    'brand' => env('ADMIN_BRAND'),

    // Admin dates are shown in this time zone and format.
    'timezone'        => env('ADMIN_TIMEZONE', 'Asia/Karachi'),
    'date_format'     => 'd M Y',
    'datetime_format' => 'd M Y, h:i A',

    // Rows per page in admin lists.
    'per_page' => 15,

    /*
    | Who can do what.
    | Every admin has ONE role. "permissions" are the sections of the panel the role may open
    | ('*' = everything). Menu items and routes ask for these permission names.
    |   super -> IT support / website owner: everything, including adding other admins
    |   staff -> the person who answers customers: only the Inbox (contact messages + quotes)
    */
    'super_role' => 'super',
    'default_role' => 'staff',
    'roles' => [
        'super' => ['label' => 'Super admin (IT support)', 'permissions' => ['*']],
        'staff' => ['label' => 'Inbox staff (replies to customers)', 'permissions' => ['inbox']],
    ],

    /*
    | Inbox (Contact messages + Quote requests share this workflow).
    | "workflow" says which status a record gets when it arrives, when an admin
    | opens it and when a reply is sent. "statuses" is the list shown in filters.
    */
    'inbox' => [
        'workflow' => [
            'initial'  => 'new',
            'opened'   => 'read',
            'answered' => 'replied',
        ],
        'statuses' => [
            'new'     => ['label' => 'New',     'tone' => 'info'],
            'read'    => ['label' => 'Read',    'tone' => 'neutral'],
            'replied' => ['label' => 'Replied', 'tone' => 'success'],
        ],
    ],

    /*
    | Email notifications.
    | The receiving address is read from the website settings table (key below).
    | If that is empty, the "fallback_to" address from .env (INBOX_NOTIFY_TO) is used.
    */
    'notifications' => [
        'settings_key' => 'CONTACT_EMAIL',
        'fallback_to'  => env('INBOX_NOTIFY_TO'),
    ],

    /*
    | Sidebar menu. A link is shown only when its route exists, so the menu
    | grows by itself as each new feature is added.
    | badge => key resolved in App\View\Composers\Admin\SidebarComposer
    */
    'navigation' => [
        [
            'heading' => 'Overview',
            'items' => [
                ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'dashboard', 'active' => 'admin.dashboard'],
            ],
        ],
        [
            'heading' => 'Inbox',
            'items' => [
                ['label' => 'Contact messages', 'route' => 'admin.contact-messages.index', 'icon' => 'mail',  'active' => 'admin.contact-messages.*', 'badge' => 'contact_new', 'permission' => 'inbox'],
                ['label' => 'Quote requests',   'route' => 'admin.quote-requests.index',   'icon' => 'quote', 'active' => 'admin.quote-requests.*',   'badge' => 'quote_new', 'permission' => 'inbox'],
            ],
        ],
        [
            'heading' => 'Website content',
            'items' => [
                ['label' => 'Landing content', 'route' => 'admin.landing.index',      'icon' => 'layout',   'active' => 'admin.landing.index', 'permission' => 'content'],
                ['label' => 'Home slider',     'route' => 'admin.records.index', 'params' => ['table' => 'banners'], 'icon' => 'image', 'permission' => 'content'],
                ['label' => 'Mission & values', 'route' => 'admin.records.index', 'params' => ['table' => 'panels'], 'icon' => 'award', 'permission' => 'content'],
                ['label' => 'Inner pages',     'route' => 'admin.records.index', 'params' => ['table' => 'pages'],   'icon' => 'quote', 'permission' => 'content'],
                ['label' => 'Footer & contact', 'route' => 'admin.landing.edit',  'params' => ['group' => 'contact'], 'icon' => 'mail', 'permission' => 'content'],
                ['label' => 'Services',        'route' => 'admin.services.index',     'icon' => 'layers',   'active' => 'admin.services.*', 'permission' => 'content'],
                ['label' => 'Projects',        'route' => 'admin.projects.index',     'icon' => 'image',    'active' => 'admin.projects.*', 'permission' => 'content'],
                ['label' => 'Products',        'route' => 'admin.products.index',     'icon' => 'box',      'active' => 'admin.products.*', 'permission' => 'content'],
                ['label' => 'Team members',    'route' => 'admin.team-members.index', 'icon' => 'users',    'active' => 'admin.team-members.*', 'permission' => 'content'],
                ['label' => 'Certificates',    'route' => 'admin.certificates.index', 'icon' => 'award',    'active' => 'admin.certificates.*', 'permission' => 'content'],
                ['label' => 'News & events',   'route' => 'admin.news.index',         'icon' => 'calendar', 'active' => 'admin.news.*', 'permission' => 'content'],
                ['label' => 'Partners',        'route' => 'admin.partners.index',     'icon' => 'handshake', 'active' => 'admin.partners.*', 'permission' => 'content'],
                ['label' => 'Careers',         'route' => 'admin.records.index', 'params' => ['table' => 'openings'], 'icon' => 'briefcase', 'permission' => 'content'],
            ],
        ],
        [
            'heading' => 'Settings',
            'items' => [
                ['label' => 'Admin users', 'route' => 'admin.admin-users.index', 'icon' => 'users', 'active' => 'admin.admin-users.*', 'permission' => 'users'],
            ],
        ],
    ],
];
