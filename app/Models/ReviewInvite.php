<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * A private, single-use feedback link for one invited person (table: review_invites).
 */
class ReviewInvite extends Model
{
    protected $table = 'review_invites';

    protected $fillable = ['name', 'email', 'company', 'token', 'expires_at', 'sent_at', 'used_at', 'revoked_at'];

    protected $dates = ['expires_at', 'sent_at', 'used_at', 'revoked_at'];

    public static function newToken()
    {
        do {
            $token = Str::random(48);
        } while (static::where('token', $token)->exists());

        return $token;
    }

    public function review()
    {
        return $this->hasOne(Review::class, 'invite_id');
    }

    /** pending | used | revoked | expired */
    public function getStateAttribute()
    {
        if ($this->used_at) {
            return 'used';
        }
        if ($this->revoked_at) {
            return 'revoked';
        }
        if ($this->expires_at && $this->expires_at->isPast()) {
            return 'expired';
        }

        return 'pending';
    }

    public function getIsOpenAttribute()
    {
        return $this->state === 'pending';
    }

    public function getUrlAttribute()
    {
        return url('/feedback/' . $this->token);
    }
}
