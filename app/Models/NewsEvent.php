<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * One news story, event or hiring announcement (table: news_events).
 */
class NewsEvent extends Model
{
    protected $table = 'news_events';

    protected $fillable = [
        'type', 'title', 'slug', 'summary', 'body', 'images', 'video_url',
        'event_date', 'location', 'published_at', 'is_published',
    ];

    protected $casts = [
        'is_published'    => 'boolean',
        'event_date'      => 'date',
        'published_at'    => 'date',
        'images'          => 'array',
    ];

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function getIsEventAttribute()
    {
        return $this->type === 'event';
    }

    public function getIsHiringAttribute()
    {
        return $this->type === 'hiring';
    }

    public function getTypeLabelAttribute()
    {
        return config('news.types.' . $this->type, ucfirst($this->type));
    }

    /** The date people should see: the event day for events, the publish day for the rest. */
    public function getShownDateAttribute()
    {
        return $this->is_event && $this->event_date ? $this->event_date : $this->published_at;
    }

    /** Full web addresses of all pictures, cover first. */
    public function getImageUrlsAttribute()
    {
        $dir = trim(config('news.image.dir'), '/');

        return array_values(array_map(function ($file) use ($dir) {
            return url('/public/' . $dir . '/' . rawurlencode($file));
        }, (array) $this->images));
    }

    public function getImageUrlAttribute()
    {
        $urls = $this->image_urls;

        return $urls ? $urls[0] : null;
    }

    /**
     * How to play the optional video: ['mode' => 'iframe'|'file', 'src' => '...'] or null.
     */
    public function getVideoAttribute()
    {
        $url = trim((string) $this->video_url);
        if ($url === '') {
            return null;
        }

        if (preg_match('~(?:youtube\.com/(?:watch\?(?:.*&)?v=|embed/|shorts/)|youtu\.be/)([A-Za-z0-9_-]{6,15})~', $url, $m)) {
            return ['mode' => 'iframe', 'src' => 'https://www.youtube.com/embed/' . $m[1]];
        }
        if (preg_match('~vimeo\.com/(?:video/)?(\d+)~', $url, $m)) {
            return ['mode' => 'iframe', 'src' => 'https://player.vimeo.com/video/' . $m[1]];
        }
        if (preg_match('~\.(mp4|webm)(\?.*)?$~i', $url)) {
            return ['mode' => 'file', 'src' => $url];
        }

        return null;
    }

    /**
     * Items the public site may show: published, and Hiring items only while the Careers page really has an open position
     * (so the home page never announces a job that the Careers page cannot show).
     */
    public function scopeLive($query)
    {
        $query->where('is_published', true);

        if (!static::hasOpenRoles()) {
            $query->where('type', '!=', 'hiring');
        }

        return $query;
    }

    public static function hasOpenRoles()
    {
        static $has;

        if ($has === null) {
            try {
                $has = Schema::hasTable('tbl_job_openings') && DB::table('tbl_job_openings')->where('status', 'Yes')->exists();
            } catch (\Throwable $e) {
                $has = false;
            }
        }

        return $has;
    }

    /** Where "Read more" leads: news -> Certificates, hiring -> Careers, event -> Events page. */
    public function getLinkAttribute()
    {
        if ($this->type === 'hiring') {
            return url(config('news.careers_url'));
        }
        if ($this->type === 'event') {
            return url(config('news.events_url')) . '?event=' . $this->slug;
        }

        return static::certificatesUrl();
    }

    public static function certificatesUrl()
    {
        static $url;

        if ($url === null) {
            $slug = null;
            try {
                $slug = DB::table('tbl_cms')->where('id', config('news.certificates_cms_id'))->value('slug');
            } catch (\Throwable $e) {
                $slug = null;
            }
            $url = $slug ? url('/' . $slug . '.html') : url(config('news.certificates_url'));
        }

        return $url;
    }
}
