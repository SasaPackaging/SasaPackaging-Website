<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

/**
 * One customer / partner review (table: reviews).
 * Everything is kept in the backend; only rows with is_visible = true appear on the website.
 */
class Review extends Model
{
    protected $table = 'reviews';

    protected $fillable = ['invite_id', 'name', 'company_role', 'rating', 'comment', 'is_visible', 'sort_order', 'source'];

    protected $casts = ['is_visible' => 'boolean', 'rating' => 'integer', 'sort_order' => 'integer'];

    public function invite()
    {
        return $this->belongsTo(ReviewInvite::class, 'invite_id');
    }

    public function scopeVisible($query)
    {
        return $query->where('is_visible', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderByDesc('id');
    }

    public function getStarsAttribute()
    {
        $r = max(0, min(5, (int) $this->rating));

        return str_repeat('★', $r) . str_repeat('☆', 5 - $r);
    }

    /**
     * For the home page: ['items' => [[quote, name, role, rating], ...], 'average' => float|null].
     * The average is made ONLY from the reviews that are shown. Empty when the tables do not exist yet
     * or nothing is visible, so the page falls back to what it showed before (config/landing.php).
     */
    public static function forSite()
    {
        try {
            if (!Schema::hasTable('reviews')) {
                return ['items' => [], 'average' => null];
            }

            $rows = static::visible()->ordered()->limit((int) config('reviews.site_limit', 9))->get();
        } catch (\Throwable $e) {
            return ['items' => [], 'average' => null];
        }

        return [
            'items'   => $rows->map(function ($r) {
                return ['quote' => $r->comment, 'name' => $r->name, 'role' => (string) $r->company_role, 'rating' => $r->rating];
            })->all(),
            'average' => $rows->count() ? round($rows->avg('rating'), 1) : null,
        ];
    }
}
