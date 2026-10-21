<?php

/*
|--------------------------------------------------------------------------
| Landing page content
|--------------------------------------------------------------------------
| All the fixed wording of the home page lives here, so the Blade files
| contain no hardcoded text. Edit this file to change headings, buttons,
| the machines list or customer reviews.
|
| Links: start with "/" (becomes a full URL) or "#" (anchor on the home page).
| After editing run:  php artisan config:clear
*/

return [

    'hero' => [
        'interval'   => 6500, // milliseconds between slides
        'aria_label' => 'Featured highlights',
        'more_label' => 'Learn more', // button text when a banner has none


        'gallery' => [
            'dir'     => 'assets/main/images/hero',
            'scan'    => true,                 // false = use only the 'files' list, in that order
            'files'   => ['a.png', 'b.png', 'c.png', 'd.png', 'e.png', 'f.png', 'g.png', 'h.png'],
            'columns' => 4,
        ],
        // Shown ONLY when the banners table has no active banner.
        'fallback_slides' => [
            [
                'tag'   => 'Packaging made purposeful',
                'title' => 'Smarter packaging for products that matter.',
                'text'  => 'Explore packaging solutions designed to protect your products and present your brand with confidence.',
                'btn'   => 'Explore Products',
                'link'  => '/products-and-services.html',
                'btn2'  => 'Request a Quote',
                'link2' => '/get-quote.html',
            ],
            [
                'tag'   => 'About SASA Packaging',
                'title' => 'Learn who we are and how we work.',
                'text'  => 'Discover our company, our values and the way we partner with our customers.',
                'btn'   => 'About Us',
                'link' => '/about-us.html',
                'btn2'  => '',
                'link2' => '',
            ],
            [
                'tag'   => 'Our capabilities',
                'title' => 'The machinery behind our packaging.',
                'text'  => 'A closer look at the production capabilities that support our work.',
                'btn'   => 'See Machines',
                'link' => '#machines',
                'btn2'  => '',
                'link2' => '',
            ],
        ],
    ],

    'about' => [
        'cta_label'   => 'More About Us',
        'cta_link'    => '/about-us.html',
        'video_url'   => 'https://www.youtube.com/embed/f2GGCXRq9iw',
        'video_title' => 'Where Ideas Takes Shape',
    ],

    // Numbers are counted from the database automatically.
    // Set founded_year (e.g. 2005) to also show "Years of experience".
    'stats' => [
        'eyebrow'      => 'SASA in numbers',
        'title'        => 'Trusted by industry, built on quality',
        'founded_year' => null,
        'labels' => [
            'years'      => 'Years of Experience',
            'products'   => 'Products',
            'categories' => 'Product Categories',
            'partners'   => 'Trusted Partners',
        ],
    ],

    'services' => [
        'eyebrow' => 'What we do',
        'title'   => 'Our Services',
        'lead'    => '',
    ],

    'lamination' => [
        'eyebrow' => '',
        'title'   => '',
        'lead'    => '',
    ],

    'values' => [
        'eyebrow' => 'What guides us',
        'title'   => 'Our Mission &amp; Values',
        'lead'    => 'Purpose, quality, and dependable service shape the way we work.',
    ],

    'products' => [
        'eyebrow'     => 'What we make',
        'title'       => 'Our Products',
        'explore'     => 'Explore',
        'view_all'    => 'View all products',
        'view_all_link' => '/products-and-services.html',
        'empty_title' => 'Products coming soon',
        'empty_text'  => 'Featured product categories will appear here once they are marked as featured in the admin panel.',
    ],

    'machines' => [
        'eyebrow' => 'Our capabilities',
        'title'   => 'Machines &amp; Manufacturing',
        'lead'    => 'The machinery and production capabilities behind our packaging solutions.',
        'placeholder_count' => 3,
        'placeholder_name'  => 'Machine name',
        'placeholder_text'  => 'Description to be added.',
        'placeholder_photo' => 'Machine photo coming soon',
        // Add real machines here and the placeholders disappear.
        // image = file name inside public/upload/machines/
        // Example: ['name' => 'Machine name', 'image' => 'machine-1.jpg', 'desc' => 'Short description'],
        'items' => [],
    ],


    /* In config/landing.php, REPLACE these four existing blocks with the ones below
   (certifications, news, testimonials). Keep every other block as it is.
   Then run:  php artisan config:clear                                              */

    'certifications' => [
        'eyebrow'   => 'Quality & trust',
        'title'     => 'Certified for confidence',
        'lead'      => 'Standards we are proud to meet, behind every order we make.',
        'hint'      => 'Tap a seal to celebrate',
        'image_dir' => 'upload/certifications',   // logo files go in public/upload/certifications/
        // Add as many as you like. image = optional logo file name. short = text inside the shield when there is no logo.
        'items' => [
            ['name' => 'ISO 9001 Certified', 'short' => 'ISO 9001', 'image' => '', 'note' => ''],
            ['name' => 'FSSC Certified',     'short' => 'FSSC',     'image' => '', 'note' => ''],
        ],
    ],

    'news' => [
        'eyebrow'       => 'From SASA Packaging',
        'title'         => 'News &amp; Events',
        'lead'          => 'Exhibitions, certifications and new openings, the latest from SASA Packaging.',
        'limit'         => 6,
        'read_more'      => 'Read more',
        'topics'        => ['Exhibitions', 'Certifications', 'New openings'],
        'ticker_label'  => 'News & Events',
        'ticker_empty'  => 'No updates yet — exhibitions, certifications and new openings will be announced here.',
        'empty_title'   => 'No updates yet',
        'empty_text'    => 'Exhibitions we attend, certifications we earn and new openings will be announced right here. Stay tuned.',
    ],

    'testimonials' => [
        'eyebrow'     => 'Customer feedback',
        'title'       => 'Ratings &amp; Testimonials',
        'rating'      => 5,                              // 5 = five full stars (4.5 also works)
        'rating_label' => 'Overall customer rating',
        'placeholder_count' => 3,                         // open review slots shown until real reviews are added
        'placeholder_name'  => 'Your customer\'s review',
        'placeholder_text'  => 'Genuine feedback will appear here',
        // Real reviews (the open slots disappear automatically). rating is optional (default 5).
        // Example: ['quote' => 'Real customer quote', 'name' => 'Customer name', 'role' => 'Company / role', 'rating' => 5],
        'items' => [],

        'partners_eyebrow'     => 'Our Partners',
        'partners_heading'     => 'Brands that grow with us',
        'partners_lead'        => 'We are proud to be the packaging partner behind businesses that care about quality.',
        'partner_placeholder'  => 'Partner logo',
    ],
];
