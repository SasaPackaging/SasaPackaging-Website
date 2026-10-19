<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\QuoteRequest;

/**
 * First page after login: what needs attention today.
 */
class DashboardController extends Controller
{
    public function index()
    {
        $monthStart = now()->startOfMonth();

        return view('admin.dashboard.index', [
            'stats' => [
                'contact_new'   => ContactMessage::newCount(),
                'quote_new'     => QuoteRequest::newCount(),
                'contact_month' => ContactMessage::where('created_at', '>=', $monthStart)->count(),
                'quote_month'   => QuoteRequest::where('created_at', '>=', $monthStart)->count(),
            ],
            'latestMessages' => ContactMessage::latest()->limit(5)->get(),
            'latestQuotes'   => QuoteRequest::latest()->limit(5)->get(),
        ]);
    }
}
