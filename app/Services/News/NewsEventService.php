<?php

namespace App\Services\News;

use App\Models\NewsEvent;
use Illuminate\Support\Str;

/**
 * Saving a news / event / hiring item: unique address (slug), pictures.
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
        $item->body            = $this->cleanHtml((string) ($data['body'] ?? '')) ?: null;
        $item->video_url       = ($data['video_url'] ?? null) ?: null;
        $item->event_date      = $type === 'event' ? (($data['event_date'] ?? null) ?: null) : null;
        $item->location        = $type === 'event' ? (($data['location'] ?? null) ?: null) : null;
        $item->published_at    = $data['published_at'];
        $item->is_published    = !empty($data['is_published']);

        if (!$item->exists) {
            $item->slug = $this->uniqueSlug($data['title']);
        }

        $images = (array) $item->images;

        foreach ($removeImages as $name) {
            if (in_array($name, $images, true)) {
                $this->deleteFile($name);
                $images = array_values(array_diff($images, [$name]));
            }
        }
        foreach ($uploads as $file) {
            $images[] = $this->storeFile($file, $data['title']);
        }
        $item->images = $images ?: null;

        $item->save();

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
        $dir  = public_path(trim(config('news.image.dir'), '/'));
        $name = (Str::slug(Str::limit($title, 40, '')) ?: 'news') . '-' . Str::random(6) . '.' . strtolower($file->getClientOriginalExtension());

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

    /** Keep normal formatting, remove anything that can run code. */
    protected function cleanHtml($html)
    {
        $html = strip_tags($html, '<p><br><strong><b><em><i><u><ul><ol><li><a><h2><h3><h4><blockquote><span><hr>');
        $html = preg_replace('/\son\w+\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)/i', '', $html);
        $html = preg_replace('/href\s*=\s*("|\')\s*javascript:[^"\']*\1/i', 'href="#"', $html);

        return trim($html);
    }
}
