<?php

namespace App\Services\News;

use App\Models\NewsEvent;
use App\Support\HtmlCleaner;
use Illuminate\Support\Str;

/**
 * Saving a news / event: unique address (slug), pictures.
 */
class NewsEventService
{
    /**
     * @param array $uploads        UploadedFile[] new pictures
     * @param array $removeImages   file names to remove
     */
    public function save(NewsEvent $item, array $data, array $uploads = [], array $removeImages = [])
    {
        $type = $data['type'];

        $item->type            = $type;
        $item->title           = $data['title'];
        $item->summary         = ($data['summary'] ?? null) ?: null;
        $item->body            = HtmlCleaner::clean($data['body'] ?? '') ?: null;
        $item->video_url       = ($data['video_url'] ?? null) ?: null;
        $item->event_date      = $type === 'event' ? (($data['event_date'] ?? null) ?: null) : null;
        $item->location        = $type === 'event' ? (($data['location'] ?? null) ?: null) : null;
        $item->published_at    = $data['published_at'];
        $item->is_published    = !empty($data['is_published']);

        if (!$item->exists) {
            $item->slug = $this->uniqueSlug($data['title']);
        }

        $images  = (array) $item->images;
        $removed = array_values(array_intersect($removeImages, $images));
        $images  = array_values(array_diff($images, $removed));
        $stored  = [];

        try {
            foreach ($uploads as $file) {
                $images[] = $stored[] = $this->storeFile($file, $data['title']);
            }
            $item->images = $images ?: null;
            $item->save();
        } catch (\Throwable $e) {
            // nothing was saved, so the pictures just uploaded must not stay behind
            foreach ($stored as $name) {
                $this->deleteFile($name);
            }
            throw $e;
        }

        // old pictures are deleted only after the record is safely saved
        foreach ($removed as $name) {
            $this->deleteFile($name);
        }

        return $item;
    }

    public function delete(NewsEvent $item)
    {
        foreach ((array) $item->images as $name) {
            $this->deleteFile($name);
        }
        $item->delete();
    }

    protected function uniqueSlug($title)
    {
        $base = Str::slug($title) ?: 'item';
        $slug = $base;
        $n    = 2;

        while (NewsEvent::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $n++;
        }

        return $slug;
    }

    protected function storeFile($file, $title)
    {
        $dir = public_path(trim(config('news.image.dir'), '/'));
        // the extension comes from the real file content, never from the name the browser sent
        $ext = strtolower((string) $file->guessExtension());
        $ext = $ext === 'jpeg' ? 'jpg' : $ext;
        if (!in_array($ext, config('news.image.extensions'), true)) {
            throw new \RuntimeException('Picture type not allowed.');
        }
        $name = (Str::slug(Str::limit($title, 40, '')) ?: 'news') . '-' . Str::random(6) . '.' . $ext;

        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        $file->move($dir, $name);

        return $name;
    }

    protected function deleteFile($name)
    {
        $path = public_path(trim(config('news.image.dir'), '/')) . DIRECTORY_SEPARATOR . basename($name);
        if (is_file($path)) {
            @unlink($path);
        }
    }
}
