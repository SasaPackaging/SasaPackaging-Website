<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * One partner / collaboration logo (existing table: tbl_members).
 * name = company name, slug = website link (the home page slider already reads it that way),
 * image = logo file name, status = Yes / No.
 */
class Partner extends Model
{
    public $timestamps = false;

    protected $guarded = [];

    public function getTable()
    {
        return config('partners.table');
    }

    public function getLogoUrlAttribute()
    {
        return $this->image ? url('/public/' . trim(config('partners.image.dir'), '/') . '/' . $this->image) : null;
    }

    public function getIsShownAttribute()
    {
        return $this->status === 'Yes';
    }
}
