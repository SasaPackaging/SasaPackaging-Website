<?php

namespace App\Mail\Inbox;

use App\Support\SiteSettings;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;

/** Email to the customer: the admin's reply. Customer replies go back to the company inbox. */
class InquiryReplyMail extends Mailable
{
    use Queueable;

    public $inquiry;
    public $replyBody;
    public $replySubject;

    public function __construct($inquiry, $subject, $body)
    {
        $this->inquiry      = $inquiry;
        $this->replySubject = $subject;
        $this->replyBody    = $body;
    }

    public function build()
    {
        $mail = $this->subject($this->replySubject)->view('emails.inbox.reply');

        if ($replyTo = SiteSettings::notificationEmail()) {
            $mail->replyTo($replyTo, config('site.name'));
        }

        return $mail;
    }
}
