<?php

namespace App\Services\Inbox;

use App\Mail\Inbox\InquiryReplyMail;
use App\Models\Admin;
use App\Models\InquiryReply;
use Illuminate\Support\Facades\Mail;

/**
 * Sends an admin's reply to the customer, keeps a copy and marks the record as replied.
 * If the email cannot be sent, an exception is thrown and NOTHING is saved.
 */
class ReplyService
{
    /**
     * @param \Illuminate\Database\Eloquent\Model $inquiry  ContactMessage or QuoteRequest
     */
    public function send($inquiry, Admin $admin, $subject, $body)
    {
        Mail::to($inquiry->email)->send(new InquiryReplyMail($inquiry, $subject, $body));

        $reply = $inquiry->replies()->create([
            'admin_id' => $admin->id,
            'to_email' => $inquiry->email,
            'subject'  => $subject,
            'body'     => $body,
        ]);

        $inquiry->markAsAnswered();

        return $reply;
    }
}
