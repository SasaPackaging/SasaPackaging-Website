<?php

namespace App\Services\Partners;

use App\Models\Partner;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

/**
 * Saving a partner: only columns that really exist are written (the table is an old one),
 * the logo is stored in public/upload/members, and a replaced / deleted logo file is cleaned up.
 */
class PartnerService
{
    public function save(Partner $item, array $data, UploadedFile $logo = null)
    {
        $table = $item->getTable();
        $cols  = Schema::getColumnListing($table);

        $values = [
            'name'       => trim($data['name']),
            'slug'       => ($data['slug'] ?? null) ?: '',
            'status'     => $data['status'],
            'sort_order' => ($data['sort_order'] ?? '') === '' || !isset($data['sort_order']) ? $this->nextOrder($table) : (int) $data['sort_order'],
        ];

        $newFile = null;
        $oldFile = $item->exists ? $item->image : null;

        if ($logo) {
            $newFile = $this->storeFile($logo, $values['name']);
            $values['image'] = $newFile;
        }

        $values = array_intersect_key($values, array_flip($cols));

        try {
            if ($item->exists) {
                if (in_array('updated_at', $cols, true)) {
                    $values['updated_at'] = now();
                }
                DB::table($table)->where('id', $item->id)->update($values);
            } else {
                $values = $values + $this->requiredDefaults($table, array_keys($values), $cols);
                foreach (['created_at', 'updated_at'] as $c) {
                    if (in_array($c, $cols, true)) {
                        $values[$c] = now();
                    }
                }
                $item->id = DB::table($table)->insertGetId($values);
            }
        } catch (\Throwable $e) {
            $this->deleteFile($newFile); // nothing saved, so the new logo must not stay behind
            throw $e;
        }

        if ($newFile && $oldFile) {
            $this->deleteFile($oldFile);
        }

        return $item;
    }

    public function delete(Partner $item)
    {
        $file = $item->image;
        $item->delete();
        $this->deleteFile($file);
    }

    protected function nextOrder($table)
    {
        return (int) DB::table($table)->max('sort_order') + 1;
    }

    /**
     * The old table may have extra NOT NULL columns without a default (they would make the insert fail).
     * Give those a harmless empty value.
     */
    protected function requiredDefaults($table, array $given, array $cols)
    {
        $extra = [];

        try {
            if (DB::connection()->getDriverName() === 'mysql') {
                foreach (DB::select('SHOW COLUMNS FROM `' . str_replace('`', '', $table) . '`') as $c) {
                    $name = $c->Field;
                    if ($c->Null === 'NO' && $c->Default === null && stripos($c->Extra, 'auto_increment') === false
                        && !in_array($name, $given, true) && !in_array($name, ['id', 'created_at', 'updated_at'], true)) {
                        $extra[$name] = preg_match('/int|decimal|float|double|bit/i', $c->Type) ? 0 : '';
                    }
                }
            }
        } catch (\Throwable $e) {
            // not critical: the normal insert is tried anyway
        }

        return $extra;
    }

    protected function storeFile(UploadedFile $file, $name)
    {
        $dir = public_path(trim(config('partners.image.dir'), '/'));
        // the extension comes from the real file content, never from the name the browser sent
        $ext = strtolower((string) $file->guessExtension());
        $ext = $ext === 'jpeg' ? 'jpg' : $ext;
        if (!in_array($ext, config('partners.image.extensions'), true)) {
            throw new \RuntimeException('Logo type not allowed.');
        }

        $fileName = (Str::slug(Str::limit($name, 40, '')) ?: 'partner') . '-' . Str::random(6) . '.' . $ext;

        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        $file->move($dir, $fileName);

        return $fileName;
    }

    protected function deleteFile($file)
    {
        if (!$file) {
            return;
        }
        $path = public_path(trim(config('partners.image.dir'), '/')) . DIRECTORY_SEPARATOR . basename($file);
        if (is_file($path)) {
            @unlink($path);
        }
    }
}
