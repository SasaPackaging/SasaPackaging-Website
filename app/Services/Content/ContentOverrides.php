<?php

namespace App\Services\Content;

use App\Models\SiteContent;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Schema;

/**
 * Lets the admin panel change website wording WITHOUT touching any Blade file.
 *
 * The site reads its wording with config('landing.values.title') etc. When the page loads,
 * apply() copies every edited value saved in the site_contents table over the config value.
 * No row = the default written in config/landing.php / config/site.php is used.
 */
class ContentOverrides
{
    protected static $files = [];

    /** Called once per request from AppServiceProvider::boot() */
    public static function apply()
    {
        try {
            if (!Schema::hasTable('site_contents')) {
                return;
            }

            foreach (SiteContent::all() as $row) {
                config([$row->config_key => json_decode($row->value, true)]);
            }
        } catch (\Throwable $e) {
            // never break the public website because of the admin tables
        }
    }

    /** Save (or update) one edited value. */
    public static function put($key, $value)
    {
        SiteContent::updateOrCreate(['config_key' => $key], ['value' => json_encode($value)]);
        config([$key => $value]);
    }

    /** Remove the edited value, so the file default is used again. */
    public static function forget(array $keys)
    {
        SiteContent::whereIn('config_key', $keys)->delete();
    }

    /** The value written in the config file ("landing.values.title" -> config/landing.php). */
    public static function defaultFor($key)
    {
        $parts = explode('.', $key, 2);
        $file  = $parts[0];

        if (!isset(static::$files[$file])) {
            $path = config_path($file . '.php');
            static::$files[$file] = is_file($path) ? (array) require $path : [];
        }

        return Arr::get(static::$files[$file], isset($parts[1]) ? $parts[1] : '');
    }
}
