<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Requests\Site\StoreContactRequest;
use App\Models\ContactMessage;
use App\Services\Inbox\InquiryNotifier;

/**
 * Receives the website's Contact us form (POST /common/contact_process).
 */
class ContactController extends Controller
{
    public function store(StoreContactRequest $request, InquiryNotifier $notifier)
    {
        $message = ContactMessage::create($request->payload() + $request->meta());

        $notifier->contactReceived($message);

        return response()->json([
            'status'  => 'success',
            'message' => __('inbox.contact.success'),
        ]);
    }
}
