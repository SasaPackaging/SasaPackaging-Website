<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Requests\Site\StoreQuoteRequest;
use App\Services\Inbox\InquiryNotifier;
use App\Services\Quote\QuoteSubmissionService;

/**
 * Receives the website's Get a Quote form (POST /send_quote).
 */
class QuoteController extends Controller
{
    public function store(StoreQuoteRequest $request, QuoteSubmissionService $quotes, InquiryNotifier $notifier)
    {
        $quote = $quotes->store($request->payload(), $request->file('q_file'), $request->meta());

        $notifier->quoteReceived($quote);

        return response()->json([
            'status'  => 'success',
            'message' => __('inbox.quote.success', ['reference' => $quote->reference]),
        ]);
    }
}
