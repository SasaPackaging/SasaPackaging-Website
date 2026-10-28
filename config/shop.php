<?php

/*
| Shop Now page (/shop.html): Pinterest-style product grid + product page.
| Products are managed in Admin > Shop products. Nothing about the shop is written inside the code.
| After editing run:  php artisan config:clear
*/
return [

    // Public addresses
    'url'         => '/shop.html',
    'product_url' => '/shop',                 // product page = /shop/{slug}

    // If the CMS menu already has a "Shop Now" page, it is sent to the shop page automatically (matched by name).
    // Add its address (slug) here only if the menu name is different, e.g. ['shop-now', 'our-shop'].
    'cms_slugs'   => ['shop-now', 'shop'],

    // Text shown on the website
    'page' => [
        'title'       => 'Shop Now',
        'eyebrow'     => 'SASA Packaging Shop',
        'lead'        => 'Ready-to-order packaging, picked for you. Tap a picture to see details, or order in one click.',
        'all_label'   => 'All',
        'order_label' => 'Order Now',
        'no_price'    => 'Price on request',
        'empty_title' => 'Our shop is being stocked',
        'empty_text'  => 'New products will appear here very soon. Meanwhile you can ask us for a custom quote.',
        'quote_label' => 'Get a custom quote',
    ],

    // Price is shown as  "{currency} 1,250"
    'currency' => 'Rs.',

    // "Order Now" opens WhatsApp with a ready message (product name + link). Number: digits only, with country code.
    'whatsapp'         => env('SHOP_WHATSAPP', '923202956541'),
    'whatsapp_message' => 'Hello SASA Packaging, I would like to order: :name (:url)',

    // Website grid
    'per_page' => 24,

    // Admin
    'admin_per_page' => 15,

    // Pictures (first one is the cover). Stored in public/upload/shop
    'image' => [
        'dir'        => 'upload/shop',
        'max_kb'     => 4096,
        'max_count'  => 8,
        'extensions' => ['jpg', 'jpeg', 'png', 'webp'],
    ],
];
