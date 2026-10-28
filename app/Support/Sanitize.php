<?php

namespace App\Support;

/**
 * Small helpers that clean what visitors type, BEFORE it is validated and saved.
 * They never throw: whatever comes in (text, array, null) a safe value goes out.
 */
class Sanitize
{
    /**
     * Cleans one value.
     *  - removes invisible control characters (they can break emails / databases / logs)
     *  - $multiline = false  -> also turns line breaks into spaces (names, subjects, e-mails ...)
     *  - non-text values (arrays, objects) become '' so "q=1&q[]=2" tricks cannot crash the page
     */
    public static function text($value, $multiline = false)
    {
        if (!is_scalar($value)) {
            return '';
        }

        $value = (string) $value;

        // invalid UTF-8 would crash some string functions and the database
        if (!mb_check_encoding($value, 'UTF-8')) {
            $value = mb_convert_encoding($value, 'UTF-8', 'UTF-8');
        }

        // control characters except tab / line feed / carriage return
        $value = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $value);

        if (!$multiline) {
            $value = preg_replace('/\s+/u', ' ', (string) $value);
        } else {
            $value = str_replace("\r\n", "\n", (string) $value);
        }

        return trim((string) $value);
    }

    /**
     * Cleans every text value of an array (recursively). Uploaded files are left alone.
     *
     * @param array $multiline  keys that may contain line breaks (message boxes)
     */
    public static function deep(array $input, array $multiline = [])
    {
        foreach ($input as $key => $value) {
            if (is_array($value)) {
                $input[$key] = static::deep($value, $multiline);
            } elseif (is_string($value)) {
                $input[$key] = static::text($value, in_array($key, $multiline, true));
            }
        }

        return $input;
    }

    /** A search box / filter value from the URL: always a short plain string. */
    public static function query($value, $max = 100)
    {
        return mb_substr(static::text($value), 0, $max);
    }

    /** Escapes % _ \ so a search word is looked up literally in a LIKE query. */
    public static function like($term)
    {
        return '%' . addcslashes((string) $term, '%_\\') . '%';
    }
}
