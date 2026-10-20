<?php

/*
| Site-wide values used by layouts (not tied to one page).
| After editing run:  php artisan config:clear
*/

return [
    'name'      => 'SASA Packaging',
    'whatsapp'  => '+923202956541', // the floating WhatsApp button
    'theme_color' => '#84a282',


    /* Footer wording (edit in Admin > Footer & contact) */
    'footer' => [
        'quick_links_title' => 'Quick Links',
        'home_label'        => 'Home',
        'machines_label'    => 'Machines',
        'contact_title'     => 'Contact Us',
        'map_title'         => 'Find Us',
        'map_embed_url'     => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d13594.09579477264!2d74.23901098715818!3d31.592100000000002!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39191d64441b8c19%3A0xf102c3c0378e80d1!2sSASA%20PACKAGING!5e0!3m2!1sen!2s!4v1683013663221!5m2!1sen!2s',
        'copyright_text'    => 'All Rights Reserved.',
        'back_to_top_label' => 'Back to top',
    ],

    'hidden_pages' => [
        'our-portfolio',
    ],
];
