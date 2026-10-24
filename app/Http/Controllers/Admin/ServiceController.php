<?php

namespace App\Http\Controllers\Admin;

/** Admin > Services (child pages of "Products & Services"). */
class ServiceController extends CatalogController
{
    protected function type()
    {
        return 'services';
    }
}
