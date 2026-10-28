<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

/**
 * One machine of the Machines / Automation page (table: machines).
 */
class Machine extends Model
{
    protected $table = 'machines';

    protected $fillable = ['name', 'description', 'image', 'sort_order', 'is_active'];

    protected $casts = ['is_active' => 'boolean', 'sort_order' => 'integer'];

    public function scopeLive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }

    /** The ONE place that turns a stored file name into a web address (change here when pictures move to Cloudinary). */
    public static function imageUrl($file)
    {
        return url('/public/' . trim(config('machines.image.dir'), '/') . '/' . rawurlencode($file));
    }

    public function getImageUrlAttribute()
    {
        return $this->image ? static::imageUrl($this->image) : null;
    }

    /**
     * Machines for the website as plain arrays [name, desc, url]. Empty when the table does not exist yet
     * or has no live rows, so the page falls back to what it showed before (config/landing.php, then placeholders).
     */
    public static function forSite()
    {
        try {
            if (!Schema::hasTable('machines')) {
                return [];
            }

            return static::live()->ordered()->get()->map(function ($m) {
                return ['name' => $m->name, 'desc' => (string) $m->description, 'url' => $m->image_url];
            })->all();
        } catch (\Throwable $e) {
            return [];
        }
    }
}
