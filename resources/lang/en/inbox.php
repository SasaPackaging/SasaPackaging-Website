<?php

/*
| Texts used by the Contact / Quote forms and their emails.
| Edit the wording here, not in the code.
*/

return [

    // Shown to the visitor after submitting a form (the website reads "message" from the JSON reply).
    'contact' => [
        'success' => 'Thank you! Your message has been sent. Our team will get back to you soon.',
    ],
    'quote' => [
        'success' => 'Thank you! Your quote request has been received. Your reference is :reference.',
    ],
    'error' => [
        'generic'   => 'Something went wrong. Please try again.',
        'too_many'  => 'Too many attempts. Please wait a minute and try again.',
        'file_type' => 'This file type is not allowed. Allowed: :types.',
    ],

    // Notification emails sent to the company
    'mail' => [
        'contact_subject' => 'New contact message: :subject',
        'quote_subject'   => 'New quote request :reference from :name',
        'open_in_admin'   => 'Open in admin panel',
        'reply_hint'      => 'You can reply to this email to answer the customer directly.',
        'reply_footer'    => 'You are receiving this because you contacted :site.',
        'your_message'    => 'Your original message',
    ],
];
