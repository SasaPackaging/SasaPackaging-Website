<?php

/*
| Reviews & ratings: invite-only feedback, the admin picks what shows on the website.
| After editing run:  php artisan config:clear
*/
return [
    'invite_days'   => 30,      // an invite link works this many days (admin can change it per invite)
    'site_limit'    => 3,       // most reviews shown on the home page (all of them stay in the admin)
    'admin_per_page' => 15,

    'comment_min' => 10,
    'comment_max' => 800,

    // Wording of the public feedback page
    'page' => [
        'title'        => 'Share your feedback',
        'lead'         => 'Thank you for working with us. Your honest words help us improve.',
        'rating_label' => 'How would you rate your experience?',
        'comment_label' => 'Your feedback',
        'role_label'   => 'Your company / role',
        'submit'       => 'Send feedback',
        'thanks_title' => 'Thank you!',
        'thanks_text'  => 'Your feedback has been received. Our team reviews it before it appears on the website.',
        'used_title'   => 'Feedback already sent',
        'used_text'    => 'This link was already used. Thank you for your feedback!',
        'expired_title' => 'This link is no longer active',
        'expired_text'  => 'It has expired or was cancelled. Please contact us and we will send a new link.',
        'invalid_title' => 'Link not found',
        'invalid_text'  => 'Please check the link in your email, or contact us.',
    ],

    // Invite email
    'mail' => [
        'subject' => 'We would love your feedback, :name',
        'intro'   => 'Thank you for being a valued partner of :site. Would you share a short review of your experience with us? It takes less than a minute.',
        'button'  => 'Give feedback',
        'note'    => 'This private link is only for you and works once. It expires on :date.',
    ],
];
