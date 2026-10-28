<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Requests\Site\StoreQuoteRequest;
use App\Services\Inbox\InquiryNotifier;
use App\Services\Quote\QuoteSubmissionService;
use Illuminate\Support\Facades\Log;

/**
 * Receives the website's Get a Quote form (POST /send_quote).
 */
class QuoteController extends Controller
{
    public function store(StoreQuoteRequest $request, QuoteSubmissionService $quotes, InquiryNotifier $notifier)
    {
        // Bots fill the hidden "website" box: pretend it worked, save nothing.
        if ($request->isSpam()) {
            return response()->json([
                'status'  => 'success',
                'message' => __('inbox.contact.success'),
            ]);
        }

        try {
            $quote = $quotes->store($request->payload(), $request->file('q_file'), $request->meta());
        } catch (\Throwable $e) {
            // The service already removed the uploaded file; show a calm message, keep details in the log.
            Log::error('Quote form: could not save the request: ' . $e->getMessage());

            return response()->json([
                'status'  => 'error',
                'message' => __('inbox.error.generic'),
            ], 500);
        }

        // Saved already - an email problem is logged inside the notifier and never loses the request.
        $notifier->quoteReceived($quote);

        return response()->json([
            'status'  => 'success',
            'message' => __('inbox.quote.success', ['reference' => $quote->reference]),
        ]);
    }
}
