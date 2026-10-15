<?php

/*
|--------------------------------------------------------------------------
| About pages - certificate details (optional)
|--------------------------------------------------------------------------
| Images go in  public/assets/main/images/about-images/
|   certificate-iso-9001.png        -> seal/logo shown inside the shield   (key = iso-9001)
|   certificate-iso-9001-doc.jpg    -> optional full certificate (opens big when "View certificate" is pressed)
| Without an entry below the name is made from the file name ("iso-9001" -> "ISO 9001").
| Add an entry (key = the part after "certificate-") to give it a proper name, issuer, year and text.
| After editing run:  php artisan config:clear
*/

return [
    'certificates' => [
        // 'iso-9001' => [
        //     'name'   => 'ISO 9001 Certified',
        //     'short'  => 'ISO 9001',
        //     'issuer' => 'Issuing body name',
        //     'year'   => '2024',
        //     'note'   => 'One or two lines about what this certificate means for customers.',
        // ],
    ],
    'celebrate_label' => 'Celebrate',
];
