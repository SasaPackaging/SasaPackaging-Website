<?php

namespace App\Http\Controllers\Admin;

use App\Models\ContactMessage;

/**
 * Admin inbox for the Contact us form.
 */
class ContactMessageController extends InboxController
{
    protected function model()
    {
        return ContactMessage::class;
    }

    protected function routeBase()
    {
        return 'admin.contact-messages';
    }

    protected function viewBase()
    {
        return 'admin.contact-messages';
    }

    protected function title()
    {
        return 'Contact messages';
    }
}
