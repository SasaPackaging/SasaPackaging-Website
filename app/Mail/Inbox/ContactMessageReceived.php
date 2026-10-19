<?php

namespace App\Mail\Inbox;

use App\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;

/** Email to the company: "someone used the Contact us form". */
class ContactMessageReceived extends Mailable
{
    use Queueable;

    public $contact;

    public function __construct(ContactMessage $contact)
    {
        $this->contact = $contact;
    }

    public function build()
    {
        return $this
            ->replyTo($this->contact->email, $this->contact->name)
            ->subject(__('inbox.mail.contact_subject', ['subject' => $this->contact->subject]))
            ->view('emails.inbox.contact-received');
    }
}
