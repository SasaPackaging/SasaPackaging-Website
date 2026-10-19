<?php

namespace App\Services\Inbox;

use App\Mail\Inbox\ContactMessageReceived;
use App\Mail\Inbox\QuoteRequestReceived;
use App\Models\ContactMessage;
use App\Models\QuoteRequest;
use App\Support\SiteSettings;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * Emails the company when a new contact message / quote request arrives.
 * The record is already saved before this runs, so a mail problem never loses a customer's message:
 * the error is written to storage/logs and the admin still sees the record in the inbox.
 */
class InquiryNotifier
{
    public function contactReceived(ContactMessage $message)
    {
        $this->send($message, new ContactMessageReceived($message));
    }

    public function quoteReceived(QuoteRequest $quote)
    {
        $this->send($quote, new QuoteRequestReceived($quote));
    }

    protected function send($record, $mailable)
    {
        $to = SiteSettings::notificationEmail();

        if (!$to) {
            Log::warning('Inbox notification skipped: no notification email is configured (settings key / INBOX_NOTIFY_TO).');
            return;
        }

        try {
            Mail::to($to)->send($mailable);
            $record->forceFill(['mail_sent_at' => $record->freshTimestamp()])->save();
        } catch (\Throwable $e) {
            Log::error('Inbox notification email failed: ' . $e->getMessage());
        }
    }
}
