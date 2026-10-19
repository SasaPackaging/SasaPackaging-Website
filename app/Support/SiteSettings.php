<?php

namespace App\Support;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Safe reader for the website's settings table (tbl_settings: key_name / key_value).
 * Unlike the old Setting::findByKey() it never crashes when a key or the table is missing.
 */
class SiteSettings
{
    protected static $cache;

    public static function get($key, $default = null)
    {
        $all = static::all();

        $value = isset($all[$key]) ? trim((string) $all[$key]) : '';

        return $value !== '' ? $value : $default;
    }

    public static function all()
    {
        if (static::$cache === null) {
            static::$cache = [];

            if (Schema::hasTable('tbl_settings')) {
                static::$cache = DB::table('tbl_settings')->pluck('key_value', 'key_name')->all();
            }
        }

        return static::$cache;
    }

    /** Where notification emails (new contact / quote) are delivered. */
    public static function notificationEmail()
    {
        $email = static::get(config('admin.notifications.settings_key'));

        if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $email = config('admin.notifications.fallback_to');
        }

        return ($email && filter_var($email, FILTER_VALIDATE_EMAIL)) ? $email : null;
    }

    public static function flush()
    {
        static::$cache = null;
    }
}
