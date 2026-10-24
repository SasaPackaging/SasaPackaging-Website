<?php

namespace App\Services\Media;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

/**
 * Saves / removes picture files inside /public (one place for every admin picture upload).
 */
class ImageStore
{
    /** @return string  the new file name */
    public static function store(UploadedFile $file, $dir, $name, array $allowed)
    {
        // the extension comes from the real file content, never from the name the browser sent
        $ext = strtolower((string) $file->guessExtension());
        $ext = $ext === 'jpeg' ? 'jpg' : $ext;
        if (!in_array($ext, $allowed, true)) {
            throw new \RuntimeException('Picture type not allowed.');
        }

        $target = public_path(trim($dir, '/'));
        if (!is_dir($target)) {
            mkdir($target, 0755, true);
        }

        $fileName = (Str::slug(Str::limit($name, 40, '')) ?: 'image') . '-' . Str::random(6) . '.' . $ext;
        $file->move($target, $fileName);

        return $fileName;
    }

    public static function delete($dir, $file)
    {
        if (!$file) {
            return;
        }
        $path = public_path(trim($dir, '/')) . DIRECTORY_SEPARATOR . basename($file);
        if (is_file($path)) {
            @unlink($path);
        }
    }
}
