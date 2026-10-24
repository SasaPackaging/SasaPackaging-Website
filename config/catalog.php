<?php

/*
| Services and Products (admin > Services / Products).
| Each entry describes ONE existing website table. Only columns that really exist in the table are shown.
|
| table    the website table
| where    rows that belong to this list (services = the child pages of "Products & Services", p_id 24)
| create   values every NEW row gets
| fields   name (column) | label | type: text, textarea, rich, number, select, status, image
|          image fields: dir = folder inside /public, saves = column(s) that get the file name
| slug     the page address is made from the name once, when the row is added (never changed afterwards)
*/
return [

    'images' => ['extensions' => ['jpg', 'jpeg', 'png', 'webp'], 'max_kb' => 4096],

    // addresses must be unique across all of these tables (the site finds a page by its address)
    'slug_tables' => ['tbl_cms', 'tbl_category', 'tbl_products'],

    'services' => [
        'table' => 'tbl_cms', 'label' => 'Services', 'singular' => 'service', 'icon' => 'layers',
        'about' => 'Services shown on the home page and in the Get a Quote list.',
        'where' => ['p_id' => 24],
        'create' => ['p_id' => 24, 'is_header' => 0, 'is_footer' => 'No'],
        'title' => 'heading', 'order' => 'sort_order', 'route' => 'admin.services',
        'fields' => [
            ['name' => 'name', 'label' => 'Page name', 'type' => 'text', 'required' => true],
            ['name' => 'heading', 'label' => 'Title shown on the card', 'type' => 'text', 'required' => true],
            ['name' => 'tag_line', 'label' => 'Small label (optional)', 'type' => 'text'],
            ['name' => 'short_contents', 'label' => 'Short text (shown on the card)', 'type' => 'textarea'],
            ['name' => 'full_contents', 'label' => 'Full page text', 'type' => 'rich'],
            ['name' => 'picture', 'label' => 'Picture', 'type' => 'image', 'dir' => 'upload/cms', 'saves' => ['banner', 'image'],
                'hint' => 'Shown on the home page card and at the top of the service page.'],
            ['name' => 'status', 'label' => 'Show on website', 'type' => 'status'],
            ['name' => 'sort_order', 'label' => 'Order (1 comes first)', 'type' => 'number'],
        ],
    ],

    'products' => [
        'table' => 'tbl_products', 'label' => 'Products', 'singular' => 'product', 'icon' => 'box',
        'about' => 'Products shown on the Products page and inside their category.',
        'where' => [], 'create' => [],
        'title' => 'name', 'order' => 'sort_order', 'route' => 'admin.products',
        'fields' => [
            ['name' => 'name', 'label' => 'Product name', 'type' => 'text', 'required' => true],
            ['name' => 'product_code', 'label' => 'Product code (optional)', 'type' => 'text'],
            ['name' => 'category_id', 'label' => 'Category', 'type' => 'select', 'options' => 'categories', 'required' => true],
            ['name' => 'tag_line', 'label' => 'Small label (optional)', 'type' => 'text'],
            ['name' => 'short_contents', 'label' => 'Short text (optional)', 'type' => 'textarea'],
            ['name' => 'FullContents', 'label' => 'Description', 'type' => 'rich'],
            ['name' => 'picture', 'label' => 'Picture', 'type' => 'image', 'dir' => 'upload/products', 'saves' => ['image'],
                'hint' => 'The product photo shown in lists and on the product page.'],
            ['name' => 'banner_picture', 'label' => 'Wide banner picture (optional)', 'type' => 'image', 'dir' => 'upload/products', 'saves' => ['banner'],
                'hint' => 'The big picture at the top of the product page. Leave it if you have none.'],
            ['name' => 'status', 'label' => 'Show on website', 'type' => 'status'],
            ['name' => 'sort_order', 'label' => 'Order (1 comes first)', 'type' => 'number'],
        ],
    ],
];
