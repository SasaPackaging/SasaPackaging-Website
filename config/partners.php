<?php

/*
| Partners / collaborations: the logo slider on the home page ("Our Partners").
| Rows live in the existing table tbl_members; logo files go to public/upload/members/.
| Only partners that are "shown" AND have a logo appear on the website.
*/
return [
    'table' => 'tbl_members',

    'image' => [
        'dir'        => 'upload/members',            // inside /public
        'max_kb'     => 2048,
        'extensions' => ['jpg', 'jpeg', 'png', 'webp'],
    ],
];
