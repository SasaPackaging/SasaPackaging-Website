<?php

namespace App\Services\Partners;

use App\Models\Partner;
use App\Services\Media\ImageStore;
use App\Support\LegacyTable;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

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
            $newFile = ImageStore::store($logo, config('partners.image.dir'), $values['name'], config('partners.image.extensions'));
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
                $values = $values + LegacyTable::requiredDefaults($table, array_keys($values));
                foreach (['created_at', 'updated_at'] as $c) {
                    if (in_array($c, $cols, true)) {
                        $values[$c] = now();
                    }
                }
                $item->id = DB::table($table)->insertGetId($values);
            }
        } catch (\Throwable $e) {
            ImageStore::delete(config('partners.image.dir'), $newFile); // nothing saved, so the new logo must not stay behind
            throw $e;
        }

        if ($newFile && $oldFile) {
            ImageStore::delete(config('partners.image.dir'), $oldFile);
        }

        return $item;
    }

    public function delete(Partner $item)
    {
        $file = $item->image;
        $item->delete();
        ImageStore::delete(config('partners.image.dir'), $file);
    }

    protected function nextOrder($table)
    {
        return (int) DB::table($table)->max('sort_order') + 1;
    }
}
