<?php

namespace App\Services\Shop;

use App\Models\ShopProduct;
use App\Services\Media\ImageStore;
use App\Support\HtmlCleaner;
use Illuminate\Support\Str;

/**
 * Saving a shop product: unique address (slug), pictures (first = cover), cover shape.
 */
class ShopProductService
{
    /**
     * @param array $uploads       UploadedFile[] new pictures
     * @param array $removeImages  file names to remove
     */
    public function save(ShopProduct $item, array $data, array $uploads = [], array $removeImages = [])
    {
        $item->name        = trim($data['name']);
        $item->category    = ($data['category'] ?? null) ? trim($data['category']) : null;
        $item->summary     = ($data['summary'] ?? null) ? trim($data['summary']) : null;
        $item->description = HtmlCleaner::clean($data['description'] ?? '') ?: null;
        $item->price       = ($data['price'] ?? '') === '' || !isset($data['price']) ? null : $data['price'];
        $item->min_order   = ($data['min_order'] ?? null) ? trim($data['min_order']) : null;
        $item->sort_order  = (int) ($data['sort_order'] ?? 0);
        $item->is_featured = !empty($data['is_featured']);
        $item->is_active   = !empty($data['is_active']);

        if (!$item->exists) {
            $item->slug = $this->uniqueSlug($item->name);
        }

        $dir     = config('shop.image.dir');
        $allowed = config('shop.image.extensions');
        $images  = (array) $item->images;
        $removed = array_values(array_intersect($removeImages, $images));
        $images  = array_values(array_diff($images, $removed));
        $stored  = [];

        try {
            foreach ($uploads as $file) {
                $images[] = $stored[] = ImageStore::store($file, $dir, $item->name, $allowed);
            }
            $item->images      = $images ?: null;
            $item->cover_ratio = $images ? $this->ratioOf($dir, $images[0]) : null;
            $item->save();
        } catch (\Throwable $e) {
            // nothing was saved, so the pictures just uploaded must not stay behind
            foreach ($stored as $name) {
                ImageStore::delete($dir, $name);
            }
            throw $e;
        }

        // old pictures are deleted only after the record is safely saved
        foreach ($removed as $name) {
            ImageStore::delete($dir, $name);
        }

        return $item;
    }

    public function delete(ShopProduct $item)
    {
        $files = (array) $item->images;
        $item->delete();

        foreach ($files as $name) {
            ImageStore::delete(config('shop.image.dir'), $name);
        }
    }

    /** Width / height of a stored picture (null when it cannot be read). */
    protected function ratioOf($dir, $file)
    {
        $path = public_path(trim($dir, '/')) . DIRECTORY_SEPARATOR . basename($file);
        $size = is_file($path) ? @getimagesize($path) : false;

        if (!$size || empty($size[0]) || empty($size[1])) {
            return null;
        }

        // keep it inside a sensible range so one extreme picture cannot break the grid
        return round(max(0.5, min(2.0, $size[0] / $size[1])), 4);
    }

    protected function uniqueSlug($name)
    {
        $base = Str::slug($name) ?: 'product';
        $slug = $base;
        $n    = 2;

        while (ShopProduct::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $n++;
        }

        return $slug;
    }
}
