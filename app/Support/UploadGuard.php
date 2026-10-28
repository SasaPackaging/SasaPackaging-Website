<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;

/**
 * Extra checks for files visitors upload (CV, artwork).
 * The file NAME can be faked, so we also look at what the file really is.
 */
class UploadGuard
{
    /** Real content types we accept for each extension. null = no reliable type exists, so no type check. */
    const MIME_MAP = [
        'pdf'  => ['application/pdf'],
        // pictures: customers often save a .webp / .png under a .jpg name, so any real picture type is fine
        'jpg'  => ['image/jpeg', 'image/png', 'image/gif', 'image/webp'],
        'jpeg' => ['image/jpeg', 'image/png', 'image/gif', 'image/webp'],
        'png'  => ['image/png', 'image/jpeg', 'image/gif', 'image/webp'],
        'gif'  => ['image/gif', 'image/png', 'image/jpeg', 'image/webp'],
        'doc'  => ['application/msword', 'application/CDFV2', 'application/x-ole-storage', 'application/vnd.ms-office', 'application/octet-stream'],
        'docx' => ['application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/zip', 'application/octet-stream'],
        'xlsx' => ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/zip', 'application/octet-stream'],
        'ai'   => null, // Illustrator files are reported as pdf / postscript / octet-stream
        'cdr'  => null, // CorelDRAW has no reliable type
    ];

    /** Is it a real, fully uploaded file? */
    public static function isUsable($file)
    {
        return $file instanceof UploadedFile && $file->isValid();
    }

    /** True when the real content type fits the extension (or no check is possible for it). */
    public static function mimeMatchesExtension(UploadedFile $file, $ext)
    {
        $ext = strtolower((string) $ext);

        if (!array_key_exists($ext, self::MIME_MAP)) {
            return false; // unknown extension: not allowed
        }
        if (self::MIME_MAP[$ext] === null) {
            return true;
        }

        return in_array((string) $file->getMimeType(), self::MIME_MAP[$ext], true);
    }

    /** True when the file contains server-side script code (a disguised PHP file). */
    public static function looksLikeScript(UploadedFile $file)
    {
        $path = $file->getRealPath();
        if (!$path || !is_readable($path)) {
            return true; // cannot inspect it, so do not trust it
        }

        $handle = @fopen($path, 'rb');
        if (!$handle) {
            return true;
        }
        $head = (string) fread($handle, 16384);
        fclose($handle);

        return (bool) preg_match('/<\?(php|=)|<\?\s|<script\b|<%/i', $head);
    }
}
