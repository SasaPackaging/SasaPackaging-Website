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
                ['label' => 'Contact messages', 'route' => 'admin.contact-messages.index', 'icon' => 'mail',  'active' => 'admin.contact-messages.*', 'badge' => 'contact_new'],
                ['label' => 'Quote requests',   'route' => 'admin.quote-requests.index',   'icon' => 'quote', 'active' => 'admin.quote-requests.*',   'badge' => 'quote_new'],
            ],
        ],
        [
            'heading' => 'Website content',
            'items' => [
                ['label' => 'Landing content', 'route' => 'admin.landing.index',      'icon' => 'layout',   'active' => 'admin.landing.*'],
                ['label' => 'Services',        'route' => 'admin.services.index',     'icon' => 'layers',   'active' => 'admin.services.*'],
                ['label' => 'Projects',        'route' => 'admin.projects.index',     'icon' => 'image',    'active' => 'admin.projects.*'],
                ['label' => 'Products',        'route' => 'admin.products.index',     'icon' => 'box',      'active' => 'admin.products.*'],
                ['label' => 'Team members',    'route' => 'admin.team-members.index', 'icon' => 'users',    'active' => 'admin.team-members.*'],
                ['label' => 'Certificates',    'route' => 'admin.certificates.index', 'icon' => 'award',    'active' => 'admin.certificates.*'],
                ['label' => 'News & events',   'route' => 'admin.news.index',         'icon' => 'calendar', 'active' => 'admin.news.*'],
                ['label' => 'Partners',        'route' => 'admin.partners.index',     'icon' => 'handshake', 'active' => 'admin.partners.*'],
                ['label' => 'Careers',         'route' => 'admin.careers.index',      'icon' => 'briefcase', 'active' => 'admin.careers.*'],
            ],
        ],
    ],
];
