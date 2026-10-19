<?php

namespace App\Models\Concerns;

use App\Models\InquiryReply;
use Illuminate\Support\Str;

/**
 * Behaviour shared by everything that lands in the admin inbox
 * (Contact messages and Quote requests): status, search, replies.
 *
 * The model using this trait must define:
 *   public static function searchableColumns(): array   // columns the search box looks in
 */
trait InboxItem
{
    /* ---------- workflow (names come from config/admin.php) ---------- */

    public static function initialStatus()
    {
        return config('admin.inbox.workflow.initial');
    }

    public static function statuses()
    {
        return config('admin.inbox.statuses');
    }

    public static function statusKeys()
    {
        return array_keys(static::statuses());
    }

    protected static function bootInboxItem()
    {
        // Every new record starts with the "initial" status, whoever creates it.
        static::creating(function ($item) {
            if (empty($item->status)) {
                $item->status = static::initialStatus();
            }
        });

        static::deleting(function ($item) {
            $item->replies()->delete();
        });
    }

    public function isNew()
    {
        return $this->status === config('admin.inbox.workflow.initial');
    }

    public function getStatusLabelAttribute()
    {
        return config('admin.inbox.statuses.' . $this->status . '.label', Str::title($this->status));
    }

    public function getStatusToneAttribute()
    {
        return config('admin.inbox.statuses.' . $this->status . '.tone', 'neutral');
    }

    /** Called when an admin opens the record: "new" becomes "read". Nothing else changes. */
    public function markAsOpened()
    {
        if ($this->isNew()) {
            $this->status  = config('admin.inbox.workflow.opened');
            $this->read_at = $this->freshTimestamp();
            $this->save();
        }
    }

    /** Called after a reply email was sent. */
    public function markAsAnswered()
    {
        $this->status     = config('admin.inbox.workflow.answered');
        $this->replied_at = $this->freshTimestamp();
        if (!$this->read_at) {
            $this->read_at = $this->replied_at;
        }
        $this->save();
    }

    /** Admin picked a status by hand. Keeps the read / replied dates in step. */
    public function changeStatus($status)
    {
        $this->status = $status;

        if ($status !== config('admin.inbox.workflow.initial') && !$this->read_at) {
            $this->read_at = $this->freshTimestamp();
        }
        if ($status === config('admin.inbox.workflow.answered') && !$this->replied_at) {
            $this->replied_at = $this->freshTimestamp();
        }
        $this->save();
    }

    /* ---------- query helpers ---------- */

    public function scopeFilterStatus($query, $status)
    {
        if ($status && in_array($status, static::statusKeys(), true)) {
            $query->where($this->getTable() . '.status', $status);
        }

        return $query;
    }

    public function scopeSearch($query, $term)
    {
        $term = trim((string) $term);
        if ($term === '') {
            return $query;
        }

        // escape % and _ so they are searched literally
        $like = '%' . addcslashes($term, '%_\\') . '%';

        return $query->where(function ($q) use ($like) {
            foreach (static::searchableColumns() as $column) {
                $q->orWhere($this->getTable() . '.' . $column, 'like', $like);
            }
        });
    }

    /** ['all' => 12, 'new' => 3, 'read' => 5, 'replied' => 4] for the filter tabs. */
    public static function statusCounts()
    {
        $found  = static::query()->selectRaw('status, COUNT(*) as total')->groupBy('status')->pluck('total', 'status');
        $counts = ['all' => (int) $found->sum()];

        foreach (static::statusKeys() as $key) {
            $counts[$key] = (int) $found->get($key, 0);
        }

        return $counts;
    }

    public static function newCount()
    {
        return static::query()->where('status', static::initialStatus())->count();
    }

    /* ---------- relations ---------- */

    public function replies()
    {
        return $this->morphMany(InquiryReply::class, 'inquiry')->latest('id');
    }
}
