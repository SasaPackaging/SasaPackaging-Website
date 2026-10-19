<?php

/*
|--------------------------------------------------------------------------
| "Get a Quote" form options
|--------------------------------------------------------------------------
| The drop-downs on the quote page, the validation rules and the admin inbox
| all read these lists, so there is one place to edit them.
| Format:  'value saved in database' => 'text shown to the visitor'
| After editing run:  php artisan config:clear
*/

return [

    'units' => [
        'CM'     => 'CM',
        'INCHES' => 'INCHES',
        'MM'     => 'MM',
        'FEET'   => 'FEET',
    ],

    'stocks' => [
        '208 GSM Bleach card' => '208 GSM Bleach card',
        '210 GSM Bleach card' => '210 GSM Bleach card',
        '220 GSM Bleach card' => '220 GSM Bleach card',
        '240 GSM Bleach card' => '240 GSM Bleach card',
        '300 GSM Bleach card' => '300 GSM Bleach card',
        '250 GSM Box Board'   => '250 GSM Box Board',
        '300 GSM Box Board'   => '300 GSM Box Board',
        '350 GSM Box Board'   => '350 GSM Box Board',
        '148 GSM Matt Paper'  => '148 GSM Matt Paper',
        'Kraft Paper 90 GSM'  => 'Kraft Paper 90 GSM',
        'Kraft Paper 112 GSM' => 'Kraft Paper 112 GSM',
        'Kraft Paper 125 GSM' => 'Kraft Paper 125 GSM',
        'Rigid'               => 'Rigid',
    ],

    'coatings' => [
        'Gloss Lamination' => 'Shine Lamination',
        'Matte Lamination' => 'Matt Lamination',
        'Gloss Varnish'    => 'Shine Varnish',
        'Matte Varnish'    => 'Matt Varnish',
    ],

    'add_ons' => [
        'Gold Foiling'    => 'Gold Foiling',
        'Silver Foiling'  => 'Silver Foiling',
        'Embossing'       => 'Embossing',
        'Spot UV'         => 'Spot UV',
        'window Patching' => 'window Patching',
    ],

    'colours' => [
        '1 Colour'                    => '1 Colour',
        '2 Colours'                   => '2 Colours',
        '3 Colours'                   => '3 Colours',
        '4 Colours'                   => '4 Colours',
        '5 Colours'                   => '5 Colours',
        '3 Colours / 1 Special Colour' => '3 Colours / 1 Special Colour',
        '4 Colours / 1 Special Colour' => '4 Colours / 1 Special Colour',
    ],

    'packings' => [
        'Bags'   => 'Bags',
        'Carton' => 'Carton',
    ],

    // Artwork / reference file the customer can attach.
    'file' => [
        'max_kb'     => 15360, // 15 MB
        'extensions' => ['jpg', 'jpeg', 'png', 'gif', 'docx', 'xlsx', 'pdf', 'ai', 'cdr'],
        'disk'       => 'local',        // private: only admins can download it
        'directory'  => 'quote-files',
    ],

    // Reference number given to every request, e.g. Q-00042.
    'reference_prefix' => 'Q-',
    'reference_pad'    => 5,

    /*
    | Where the "Product" and "Service" drop-downs get their choices.
    | They read the website's own tables, so whatever you add in the admin
    | (Products / Services) shows up here automatically. If a table or column
    | does not exist yet, that drop-down is simply hidden.
    */
    'catalog' => [
        'products' => [
            'table'  => 'tbl_products',
            'id'     => 'id',
            'label'  => 'name',
            'active' => ['column' => 'status', 'value' => 'Yes'],
            'order'  => 'sort_order',
        ],
        'services' => [
            'table'  => 'tbl_services',
            'id'     => 'id',
            'label'  => 'name',
            'active' => ['column' => 'status', 'value' => 'Yes'],
            'order'  => 'sort_order',
        ],
    ],
];
