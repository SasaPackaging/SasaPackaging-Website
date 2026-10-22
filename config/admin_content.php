<?php

/*
|--------------------------------------------------------------------------
| What the admin can edit on the public website
|--------------------------------------------------------------------------
| 'groups'  -> forms for the wording written in config/landing.php and config/site.php
|              (saved in the site_contents table; "Reset" returns to the file default)
|              store = 'settings' saves into tbl_settings instead (phone, email, social links ...)
| 'tables'  -> lists of records kept in existing website tables (slider, panels, inner pages)
|
| To make another piece of wording editable: add one field line here. No code needed.
|
| Field types: text | textarea | number | list | rich (tables only) | status (tables only)
|   raw => true   the website prints this text as HTML, so the admin types plain text and it is made safe.
| A "list" is a set of rows: columns => [[name, label, type(text|textarea)], ...]; scalar => true = one value per row.
*/

$text = function ($key, $label, $extra = []) {
    return array_merge(['key' => $key, 'label' => $label, 'type' => 'text'], $extra);
};

// "Application questions" choices = the profiles written in config/careers.php
$profiles = [];
foreach (array_keys((require __DIR__ . '/careers.php')['profiles']) as $key) {
    $profiles[$key] = ucfirst($key);
}

return [

    'groups' => [

        'hero' => [
            'label' => 'Top banner (hero)', 'icon' => 'layout',
            'about' => 'Slides come from "Home slider". The slides below are used only when the slider is empty.',
            'fields' => [
                $text('landing.hero.more_label', 'Button text when a slide has none'),
                ['key' => 'landing.hero.interval', 'label' => 'Time between slides in milliseconds (6500 = 6.5 seconds)', 'type' => 'number'],
                ['key' => 'landing.hero.fallback_slides', 'label' => 'Backup slides', 'type' => 'list', 'add' => 'Add slide', 'columns' => [
                    ['name' => 'tag', 'label' => 'Small label'], ['name' => 'title', 'label' => 'Heading'],
                    ['name' => 'text', 'label' => 'Text', 'type' => 'textarea'],
                    ['name' => 'btn', 'label' => 'Button text'], ['name' => 'link', 'label' => 'Button link'],
                    ['name' => 'btn2', 'label' => 'Second button text'], ['name' => 'link2', 'label' => 'Second button link'],
                ]],
            ],
        ],

        'about' => [
            'label' => 'About block', 'icon' => 'layers',
            'about' => 'The paragraph itself is edited in Inner pages > About Us.',
            'fields' => [
                $text('landing.about.cta_label', 'Button text'),
                $text('landing.about.cta_link', 'Button link'),
                $text('landing.about.video_url', 'YouTube embed link', ['hint' => 'Format: https://www.youtube.com/embed/VIDEO_ID']),
                $text('landing.about.video_title', 'Video title'),
            ],
        ],

        'stats' => [
            'label' => 'Numbers', 'icon' => 'award',
            'about' => 'The numbers are counted automatically. Only the wording is edited here.',
            'fields' => [
                $text('landing.stats.eyebrow', 'Small heading'),
                $text('landing.stats.title', 'Heading'),
                ['key' => 'landing.stats.founded_year', 'label' => 'Founded year (empty = hide "years of experience")', 'type' => 'number'],
                $text('landing.stats.labels.years', 'Label: years'),
                $text('landing.stats.labels.products', 'Label: products'),
                $text('landing.stats.labels.categories', 'Label: categories'),
                $text('landing.stats.labels.partners', 'Label: partners'),
            ],
        ],

        'services' => [
            'label' => 'Services heading', 'icon' => 'layers',
            'fields' => [
                $text('landing.services.eyebrow', 'Small heading'),
                $text('landing.services.title', 'Heading', ['raw' => true]),
                $text('landing.services.lead', 'Intro text', ['raw' => true]),
            ],
        ],

        'lamination' => [
            'label' => 'Highlight block', 'icon' => 'layout',
            'about' => 'The block text comes from Inner pages. Leave these empty to hide the heading.',
            'fields' => [
                $text('landing.lamination.eyebrow', 'Small heading'),
                $text('landing.lamination.title', 'Heading', ['raw' => true]),
                $text('landing.lamination.lead', 'Intro text', ['raw' => true]),
            ],
        ],

        'values' => [
            'label' => 'Mission & values heading', 'icon' => 'award',
            'about' => 'The panels themselves are edited in "Mission & values".',
            'fields' => [
                $text('landing.values.eyebrow', 'Small heading'),
                $text('landing.values.title', 'Heading', ['raw' => true]),
                $text('landing.values.lead', 'Intro text', ['raw' => true]),
            ],
        ],

        'products' => [
            'label' => 'Products', 'icon' => 'box',
            'fields' => [
                $text('landing.products.eyebrow', 'Small heading'),
                $text('landing.products.title', 'Heading', ['raw' => true]),
                $text('landing.products.lead', 'Intro text', ['raw' => true]),
                $text('landing.products.explore', 'Card button text'),
                $text('landing.products.view_all', '"View all" button text'),
                $text('landing.products.view_all_link', '"View all" link'),
                $text('landing.products.empty_title', 'Empty state: heading'),
                ['key' => 'landing.products.empty_text', 'label' => 'Empty state: text', 'type' => 'textarea'],
            ],
        ],

        'machines' => [
            'label' => 'Automation', 'icon' => 'box',
            'fields' => [
                $text('landing.machines.eyebrow', 'Small heading'),
                $text('landing.machines.title', 'Heading', ['raw' => true]),
                $text('landing.machines.lead', 'Intro text'),
                $text('landing.machines.placeholder_name', 'Placeholder name'),
                $text('landing.machines.placeholder_text', 'Placeholder text'),
                $text('landing.machines.placeholder_photo', 'Placeholder photo text'),
                ['key' => 'landing.machines.items', 'label' => 'Machines (placeholders disappear when you add one)', 'type' => 'list', 'add' => 'Add machine', 'columns' => [
                    ['name' => 'name', 'label' => 'Name'],
                    ['name' => 'image', 'label' => 'Image file name (in public/upload/machines/)'],
                    ['name' => 'desc', 'label' => 'Description', 'type' => 'textarea'],
                ]],
            ],
        ],

        'certifications' => [
            'label' => 'Certifications', 'icon' => 'award',
            'fields' => [
                $text('landing.certifications.eyebrow', 'Small heading'),
                $text('landing.certifications.title', 'Heading', ['raw' => true]),
                $text('landing.certifications.lead', 'Intro text', ['raw' => true]),
                $text('landing.certifications.hint', 'Hint under the seals'),
                ['key' => 'landing.certifications.items', 'label' => 'Certificates', 'type' => 'list', 'add' => 'Add certificate', 'columns' => [
                    ['name' => 'name', 'label' => 'Name'],
                    ['name' => 'short', 'label' => 'Short text in the shield'],
                    ['name' => 'image', 'label' => 'Logo file name (in public/upload/certifications/)'],
                    ['name' => 'note', 'label' => 'Note'],
                ]],
            ],
        ],

        'news' => [
            'label' => 'News & events block', 'icon' => 'calendar',
            'fields' => [
                $text('landing.news.eyebrow', 'Small heading'),
                $text('landing.news.title', 'Heading', ['raw' => true]),
                $text('landing.news.lead', 'Intro text', ['raw' => true]),
                ['key' => 'landing.news.limit', 'label' => 'How many items to show on the home page', 'type' => 'number'],
                $text('landing.news.read_more', '"Read more" text on cards'),
                $text('landing.news.hiring_label', 'Hiring card: tag'),
                $text('landing.news.hiring_title', 'Hiring card: heading'),
                ['key' => 'landing.news.hiring_text', 'label' => 'Hiring card: text (:positions = job titles, :count = how many)', 'type' => 'textarea'],
                $text('landing.news.ticker_label', 'Top ticker label'),
                ['key' => 'landing.news.ticker_empty', 'label' => 'Top ticker when empty', 'type' => 'textarea'],
                $text('landing.news.empty_title', 'Empty state: heading'),
                ['key' => 'landing.news.empty_text', 'label' => 'Empty state: text', 'type' => 'textarea'],
                ['key' => 'landing.news.topics', 'label' => 'Topic tags', 'type' => 'list', 'scalar' => true, 'add' => 'Add topic', 'columns' => [
                    ['name' => 'value', 'label' => 'Topic'],
                ]],
            ],
        ],

        'testimonials' => [
            'label' => 'Reviews & partners', 'icon' => 'handshake',
            'fields' => [
                $text('landing.testimonials.eyebrow', 'Small heading'),
                $text('landing.testimonials.title', 'Heading', ['raw' => true]),
                ['key' => 'landing.testimonials.rating', 'label' => 'Overall rating (0 to 5)', 'type' => 'number', 'step' => '0.5', 'max' => 5],
                $text('landing.testimonials.rating_label', 'Rating label'),
                $text('landing.testimonials.placeholder_name', 'Placeholder name'),
                $text('landing.testimonials.placeholder_text', 'Placeholder text'),
                ['key' => 'landing.testimonials.items', 'label' => 'Customer reviews (placeholders disappear when you add one)', 'type' => 'list', 'add' => 'Add review', 'columns' => [
                    ['name' => 'quote', 'label' => 'Review', 'type' => 'textarea'],
                    ['name' => 'name', 'label' => 'Customer name'],
                    ['name' => 'role', 'label' => 'Company / role'],
                    ['name' => 'rating', 'label' => 'Stars (1 to 5)'],
                ]],
                $text('landing.testimonials.partners_eyebrow', 'Partners: small heading'),
                $text('landing.testimonials.partners_heading', 'Partners: heading', ['raw' => true]),
                $text('landing.testimonials.partners_lead', 'Partners: intro text', ['raw' => true]),
                $text('landing.testimonials.partner_placeholder', 'Partners: placeholder text'),
            ],
        ],

        'footer' => [
            'label' => 'Footer wording', 'icon' => 'layout',
            'about' => 'The short company text in the footer is edited in Inner pages > About Us (short text).',
            'fields' => [
                $text('site.footer.quick_links_title', 'Quick links heading'),
                $text('site.footer.home_label', 'Home link text'),
                $text('site.footer.machines_label', 'Machines link text (menu and footer)'),
                $text('site.footer.contact_title', 'Contact heading'),
                $text('site.footer.map_title', 'Map heading'),
                ['key' => 'site.footer.map_embed_url', 'label' => 'Google Maps embed link', 'type' => 'textarea', 'hint' => 'Google Maps > Share > Embed a map > copy only the link inside src="..."'],
                $text('site.footer.copyright_text', 'Text after "Copyright © year name."'),
                $text('site.footer.back_to_top_label', 'Back-to-top button label'),
            ],
        ],

        'contact' => [
            'label' => 'Contact details & social links', 'icon' => 'mail', 'store' => 'settings',
            'about' => 'Shown in the footer and on the contact page. Empty social links are hidden.',
            'fields' => [
                ['key' => 'SITE_NAME', 'label' => 'Company name', 'type' => 'text'],
                ['key' => 'CONTACT_EMAIL', 'label' => 'Company email', 'type' => 'text', 'rule' => 'email', 'hint' => 'Also receives the new-message notifications.'],
                ['key' => 'PHONE', 'label' => 'Phone', 'type' => 'text'],
                ['key' => 'POBOX', 'label' => 'Second phone', 'type' => 'text'],
                ['key' => 'ADDRESS', 'label' => 'Address', 'type' => 'textarea'],
                ['key' => 'FACEBOOK', 'label' => 'Facebook link', 'type' => 'text'],
                ['key' => 'INSTAGRAM', 'label' => 'Instagram link', 'type' => 'text'],
                ['key' => 'LINKEDIN', 'label' => 'LinkedIn link', 'type' => 'text'],
                ['key' => 'YOUTUBE', 'label' => 'YouTube link', 'type' => 'text'],
            ],
        ],
    ],

    // Section cards on the hub page, in this order (group keys from above)
    'hub' => [
        'Home page'      => ['hero', 'about', 'stats', 'services', 'lamination', 'values', 'products', 'machines', 'certifications', 'news', 'testimonials'],
        'Footer & contact' => ['footer', 'contact'],
    ],

    'tables' => [

        'banners' => [
            'table' => 'tbl_banners', 'label' => 'Home slider', 'singular' => 'slide', 'icon' => 'image',
            'about' => 'The text slides at the top of the home page. Hide a slide with status "No".',
            'title' => 'name', 'order' => 'sort_order', 'creatable' => true,
            'list' => ['name' => 'Heading', 'status' => 'Status'],
            'fields' => [
                ['name' => 'name', 'label' => 'Heading', 'type' => 'text', 'required' => true],
                ['name' => 'tag_line', 'label' => 'Small label above the heading', 'type' => 'text'],
                ['name' => 'detail', 'label' => 'Text', 'type' => 'textarea'],
                ['name' => 'button_text', 'label' => 'Button text', 'type' => 'text'],
                ['name' => 'link', 'label' => 'Button link', 'type' => 'text', 'hint' => 'Example: /about-us.html'],
                ['name' => 'status', 'label' => 'Show on website', 'type' => 'status'],
                ['name' => 'sort_order', 'label' => 'Order (1 comes first)', 'type' => 'number'],
            ],
        ],

        'openings' => [
            'table' => 'tbl_job_openings', 'label' => 'Careers: open positions', 'singular' => 'position', 'icon' => 'briefcase',
            'about' => 'Jobs shown on the Careers page. When at least one position is shown here, a "We are hiring" card appears by itself in the home page News & events section.',
            'title' => 'title', 'order' => 'sort_order', 'creatable' => true, 'deletable' => true,
            'list' => ['title' => 'Position', 'department' => 'Department', 'status' => 'Status'],
            'fields' => [
                ['name' => 'title', 'label' => 'Position title', 'type' => 'text', 'required' => true],
                ['name' => 'department', 'label' => 'Department', 'type' => 'text', 'hint' => 'Example: Design, Production, Sales'],
                ['name' => 'location', 'label' => 'Location', 'type' => 'text', 'default' => 'Lahore'],
                ['name' => 'job_type', 'label' => 'Job type', 'type' => 'text', 'default' => 'Full-time', 'hint' => 'Example: Full-time, Part-time, Internship'],
                ['name' => 'profile', 'label' => 'Application questions', 'type' => 'select', 'options' => $profiles, 'hint' => 'Which extra questions the applicant sees (set in config/careers.php).'],
                ['name' => 'summary', 'label' => 'Short description', 'type' => 'textarea'],
                ['name' => 'status', 'label' => 'Show on the Careers page', 'type' => 'status'],
                ['name' => 'sort_order', 'label' => 'Order (1 comes first)', 'type' => 'number'],
            ],
        ],

        'panels' => [
            'table' => 'tbl_services', 'label' => 'Mission & values', 'singular' => 'panel', 'icon' => 'award',
            'about' => 'The expanding panels under "Our Mission & Values" (Mission, Vision, Values ...).',
            'title' => 'name', 'order' => 'sort_order', 'creatable' => false,
            'list' => ['name' => 'Title', 'status' => 'Status'],
            'fields' => [
                ['name' => 'name', 'label' => 'Title', 'type' => 'text', 'required' => true],
                ['name' => 'FullContents', 'label' => 'Text', 'type' => 'rich'],
                ['name' => 'status', 'label' => 'Show on website', 'type' => 'status'],
                ['name' => 'sort_order', 'label' => 'Order (1 comes first)', 'type' => 'number'],
            ],
        ],

        'pages' => [
            'table' => 'tbl_cms', 'label' => 'Inner pages', 'singular' => 'page', 'icon' => 'quote',
            'about' => 'About Us, Mission, Vision, Values, Strategy, CEO message and other pages.',
            'title' => 'name', 'order' => 'sort_order', 'creatable' => false,
            'list' => ['name' => 'Page', 'slug' => 'Address', 'status' => 'Status'],
            'fields' => [
                ['name' => 'name', 'label' => 'Page name (menu / footer)', 'type' => 'text', 'required' => true],
                ['name' => 'heading', 'label' => 'Page heading', 'type' => 'text'],
                ['name' => 'banner_heading', 'label' => 'Banner heading', 'type' => 'text'],
                ['name' => 'tag_line', 'label' => 'Small label', 'type' => 'text'],
                ['name' => 'short_contents', 'label' => 'Short text (also used in the footer for "About Us")', 'type' => 'rich'],
                ['name' => 'full_contents', 'label' => 'Page content', 'type' => 'rich'],
                ['name' => 'meta_title', 'label' => 'Search title (SEO)', 'type' => 'text'],
                ['name' => 'meta_description', 'label' => 'Search description (SEO)', 'type' => 'textarea'],
                ['name' => 'status', 'label' => 'Show on website', 'type' => 'status'],
                ['name' => 'is_footer', 'label' => 'Show in footer links', 'type' => 'status'],
                ['name' => 'sort_order', 'label' => 'Order (1 comes first)', 'type' => 'number'],
            ],
        ],
    ],
];
