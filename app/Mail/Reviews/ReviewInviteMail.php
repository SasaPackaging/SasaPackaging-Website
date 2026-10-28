<?php

namespace App\Mail\Reviews;

use App\Models\ReviewInvite;
use App\Support\SiteSettings;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;

/** Email to an invited person with their private feedback link. */
class ReviewInviteMail extends Mailable
{
    use Queueable;

    public $invite;

    public function __construct(ReviewInvite $invite)
    {
        $this->invite = $invite;
    }

    public function build()
    {
        $mail = $this->subject(strtr(config('reviews.mail.subject'), [':name' => $this->invite->name]))->view('emails.reviews.invite');

        if ($replyTo = SiteSettings::notificationEmail()) {
            $mail->replyTo($replyTo, config('site.name'));
        }

        return $mail;
    }
}
