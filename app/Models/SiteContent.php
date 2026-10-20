<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** One edited website setting (see App\Services\Content\ContentOverrides). */
class SiteContent extends Model
{
    protected $table = 'site_contents';

    protected $fillable = ['config_key', 'value'];
}
