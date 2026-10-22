<?php

/*
| News & events settings (admin, home page, Events page, Careers link). Nothing about them is written inside the code.
| After editing run:  php artisan config:clear
*/

return [

    // key stored in the database => label shown to people
    'types' => [
        'news'   => 'News',
        'event'  => 'Event',
    ],

    // Where "Read more" goes for each type (shown in the admin form as a reminder)
    'type_hints' => [
        'news'   => 'News (certifications, achievements): opens the Certificates page.',
        'event'  => 'Event (exhibitions): opens the Events page with this event on top.',
    ],

    // admin list: rows per page
    'per_page' => 12,

    // pictures (an event / exhibition can have many; the first one is the cover)
    'image' => [
        'dir'        => 'upload/news',    // inside /public
        'max_kb'     => 4096,
        'max_count'  => 8,
        'extensions' => ['jpg', 'jpeg', 'png', 'webp'],
    ],

    // pages the cards lead to
    'certificates_cms_id' => 20,                       // the Certificates page in tbl_cms (its address is read from there)
    'certificates_url'    => '/certificates.html',     // used only if that page cannot be found
    'careers_url'         => '/careers.html#open-positions',
    'events_url'          => '/events.html',           // the Events page (route in routes/web.php)

    // Events page wording
    'events_page' => [
        'title'            => 'Exhibitions & Events',
        'eyebrow_latest'   => 'Latest event',
        'eyebrow_selected' => 'Event',
        'crumb_home'       => 'Home',
        'photos_heading'   => 'Photos',
        'previous_heading' => 'Previous events',
        'previous_lead'    => 'Pictures and videos from our earlier events.',
        'view_event'       => 'View this event',
        'empty_title'      => 'No events yet',
        'empty_text'       => 'Exhibitions we attend will be shown here with their pictures and videos.',
        'hero_badge'       => 'SASA Packaging',
    ],

    // video links the site can show: YouTube, Vimeo or a direct .mp4 / .webm link
    'video_hosts' => ['youtube.com', 'www.youtube.com', 'm.youtube.com', 'youtu.be', 'vimeo.com', 'www.vimeo.com', 'player.vimeo.com'],
];
