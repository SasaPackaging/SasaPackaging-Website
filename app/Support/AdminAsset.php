<?php

namespace App\Support;

/**
 * URL of a file in the admin CSS / JS folder, e.g. @adminAsset('css/base.css').
 * The file's last-changed time is added so browsers reload it after you edit it.
 */
class AdminAsset
{
    public static function url($path)
    {
        $base = trim(config('admin.assets_path'), '/');
        $file = base_path($base . '/' . $path);
        $url  = url($base . '/' . $path);

        return is_file($file) ? $url . '?v=' . filemtime($file) : $url;
    }
}
