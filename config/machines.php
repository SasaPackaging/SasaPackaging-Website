<?php

/*
| Machines / Automation page (/machines.html). Machines are managed in Admin > Machines.
| The page heading and intro text are still edited in Admin > Landing content > Machines.
| After editing run:  php artisan config:clear
*/
return [
    'admin_per_page' => 15,

    // picture: one per machine, stored in public/upload/machines (same folder the page already used)
    'image' => [
        'dir'        => 'upload/machines',
        'max_kb'     => 4096,
        'extensions' => ['jpg', 'jpeg', 'png', 'webp'],
    ],
];
