<?php

namespace App\Services\Reviews;

use App\Mail\Reviews\ReviewInviteMail;
use App\Models\Review;
use App\Models\ReviewInvite;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

/**
 * Invites (private single-use links) and saving reviews.
 */
class ReviewService
{
    public function createInvite(array $data)
    {
        return ReviewInvite::create([
            'name'       => trim($data['name']),
            'email'      => strtolower(trim($data['email'])),
            'company'    => ($data['company'] ?? null) ? trim($data['company']) : null,
            'token'      => ReviewInvite::newToken(),
            'expires_at' => now()->addDays((int) ($data['days'] ?? config('reviews.invite_days'))),
        ]);
    }

    /** Sends the invite email. Throws when the mail server refuses (the invite itself stays). */
    public function sendInvite(ReviewInvite $invite)
    {
        Mail::to($invite->email)->send(new ReviewInviteMail($invite));
        $invite->forceFill(['sent_at' => now()])->save();
    }

    /**
     * Saves the feedback of an invited person. The invite is locked while saving, so two quick clicks
     * (or two tabs) can never create two reviews. Returns false when the link is no longer usable.
     * New reviews are HIDDEN until the admin shows them.
     */
    public function submit($token, array $data)
    {
        return DB::transaction(function () use ($token, $data) {
            $invite = ReviewInvite::where('token', $token)->lockForUpdate()->first();

            if (!$invite || !$invite->is_open) {
                return false;
            }

            Review::create([
                'invite_id'    => $invite->id,
                'name'         => $invite->name,
                'company_role' => ($data['company_role'] ?? null) ?: $invite->company,
                'rating'       => (int) $data['rating'],
                'comment'      => $data['comment'],
                'is_visible'   => false,
                'sort_order'   => 0,
                'source'       => 'invite',
            ]);

            $invite->forceFill(['used_at' => now()])->save();

            return true;
        });
    }

    /** Admin adds / edits a review by hand (for example feedback received by phone). */
    public function saveReview(Review $review, array $data)
    {
        $review->name         = trim($data['name']);
        $review->company_role = ($data['company_role'] ?? null) ? trim($data['company_role']) : null;
        $review->rating       = (int) $data['rating'];
        $review->comment      = trim($data['comment']);
        $review->sort_order   = (int) ($data['sort_order'] ?? 0);
        $review->is_visible   = !empty($data['is_visible']);

        if (!$review->exists) {
            $review->source = 'admin';
        }
        $review->save();

        return $review;
    }
}
