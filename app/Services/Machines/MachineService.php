<?php

namespace App\Services\Machines;

use App\Models\Machine;
use App\Services\Media\ImageStore;
use Illuminate\Http\UploadedFile;

/**
 * Saving a machine: picture is replaced / removed only after the record is safely saved.
 */
class MachineService
{
    public function save(Machine $item, array $data, UploadedFile $image = null, $removeImage = false)
    {
        $dir     = config('machines.image.dir');
        $oldFile = $item->exists ? $item->image : null;
        $newFile = null;

        $item->name        = trim($data['name']);
        $item->description = ($data['description'] ?? null) ? trim($data['description']) : null;
        $item->sort_order  = (int) ($data['sort_order'] ?? 0);
        $item->is_active   = !empty($data['is_active']);

        try {
            if ($image) {
                $newFile = ImageStore::store($image, $dir, $item->name, config('machines.image.extensions'));
                $item->image = $newFile;
            } elseif ($removeImage) {
                $item->image = null;
            }
            $item->save();
        } catch (\Throwable $e) {
            ImageStore::delete($dir, $newFile); // nothing saved, so the new picture must not stay behind
            throw $e;
        }

        if ($oldFile && $oldFile !== $item->image) {
            ImageStore::delete($dir, $oldFile);
        }

        return $item;
    }

    public function delete(Machine $item)
    {
        $file = $item->image;
        $item->delete();
        ImageStore::delete(config('machines.image.dir'), $file);
    }
}
