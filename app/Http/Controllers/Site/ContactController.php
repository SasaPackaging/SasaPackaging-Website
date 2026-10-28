<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Requests\Site\StoreContactRequest;
use App\Models\ContactMessage;
use App\Services\Inbox\InquiryNotifier;
use Illuminate\Support\Facades\Log;

/**
 * Receives the website's Contact us form (POST /common/contact_process).
 */
class ContactController extends Controller
{
    public function store(StoreContactRequest $request, InquiryNotifier $notifier)
    {
        // Bots fill the hidden "website" box: pretend it worked, save nothing.
        if ($request->isSpam()) {
            return $this->success();
        }

        try {
            $message = ContactMessage::create($request->payload() + $request->meta());
        } catch (\Throwable $e) {
            // The visitor must not see database details; we keep them in storage/logs instead.
            Log::error('Contact form: could not save the message: ' . $e->getMessage());

            return response()->json([
                'status'  => 'error',
                'message' => __('inbox.error.generic'),
            ], 500);
        }

        // Saved already - an email problem is logged inside the notifier and never loses the message.
        $notifier->contactReceived($message);

        return $this->success();
    }

    protected function success()
    {
        return response()->json([
            'status'  => 'success',
            'message' => __('inbox.contact.success'),
        ]);
    }
}
