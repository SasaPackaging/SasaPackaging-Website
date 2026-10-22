<?php

namespace App\Services\News;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * The "We are hiring" card of the News & events section.
 * It is built by itself from the open positions on the Careers page, so nobody has to write a hiring news item:
 * add a position in Admin > Careers and the card appears; hide / delete the last one and it disappears.
 */
class HiringAnnouncement
{
    /** @return array|null  same keys as the other news cards, or null when no position is open */
    public static function card()
    {
        try {
            if (!Schema::hasTable('tbl_job_openings')) {
                return null;
            }

            $rows = DB::table('tbl_job_openings')->where('status', 'Yes')->orderBy('sort_order')->get(['title', 'updated_at', 'created_at']);
        } catch (\Throwable $e) {
            return null;
        }

        if ($rows->isEmpty()) {
            return null;
        }

        $shown = (int) config('landing.news.hiring_titles_shown', 3);
        $names = $rows->pluck('title')->all();
        $list  = implode(', ', array_slice($names, 0, $shown));
        if (count($names) > $shown) {
            $list .= ' ' . str_replace(':count', count($names) - $shown, config('landing.news.hiring_more'));
        }

        $latest = $rows->map(function ($r) {
            return $r->updated_at ?: $r->created_at;
        })->filter()->max();

        return [
            'heading'   => config('landing.news.hiring_title'),
            'Contents'  => str_replace([':count', ':positions'], [count($names), $list], config('landing.news.hiring_text')),
            'image_url' => null,
            'post_date' => $latest ? date('Y-m-d', strtotime($latest)) : date('Y-m-d'),
            'type'      => config('landing.news.hiring_label'),
            'href'      => url(config('news.careers_url')),
        ];
    }
}
