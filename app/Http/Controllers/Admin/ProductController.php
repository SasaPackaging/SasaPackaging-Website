<?php

namespace App\Http\Controllers\Admin;

/** Admin > Products. */
class ProductController extends CatalogController
{
    protected function type()
    {
        return 'products';
    }
}
