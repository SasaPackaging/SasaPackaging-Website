<?php

namespace App\Mail\Inbox;

use App\Models\QuoteRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;

/** Email to the company: "someone asked for a quote". */
class QuoteRequestReceived extends Mailable
{
    use Queueable;

    public $quote;

    public function __construct(QuoteRequest $quote)
    {
        $this->quote = $quote;
    }

    public function build()
    {
        return $this
            ->replyTo($this->quote->email, $this->quote->full_name)
            ->subject(__('inbox.mail.quote_subject', ['reference' => $this->quote->reference, 'name' => $this->quote->full_name]))
            ->view('emails.inbox.quote-received');
    }
}
