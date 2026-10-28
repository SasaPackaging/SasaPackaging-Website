<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Requests\Site\SubmitFeedbackRequest;
use App\Models\ReviewInvite;
use App\Services\Reviews\ReviewService;

/**
 * Public feedback page /feedback/{token}: only a person holding a valid private link can open it, and only once.
 */
class FeedbackController extends Controller
{
    protected $service;

    public function __construct(ReviewService $service)
    {
        $this->service = $service;
    }

    public function show($token)
    {
        $invite = ReviewInvite::where('token', $token)->first();

        if (!$invite) {
            return $this->page('invalid', null, 404);
        }
        if ($invite->state === 'used') {
            return $this->page(session('feedback_thanks') ? 'thanks' : 'used', $invite);
        }
        if (!$invite->is_open) {
            return $this->page('expired', $invite, 410);
        }

        return $this->page('form', $invite);
    }

    public function store(SubmitFeedbackRequest $request, $token)
    {
        // a bot filled the hidden field: pretend it worked, save nothing
        if ($request->filled('website')) {
            return redirect('/feedback/' . $token)->with('feedback_thanks', true);
        }

        $saved = $this->service->submit($token, $request->validated());

        if (!$saved) {
            return redirect('/feedback/' . $token);   // shows "already sent" / "expired" / "not found"
        }

        return redirect('/feedback/' . $token)->with('feedback_thanks', true);
    }

    protected function page($state, $invite, $status = 200)
    {
        $page = config('reviews.page');

        return response()->view('pages.feedback', [
            'state'       => $state,
            'invite'      => $invite,
            'page'        => $page,
            'keywords'    => '',
            'title'       => $page['title'] . ' | ' . config('site.name'),
            'description' => $page['lead'],
        ], $status)->header('Referrer-Policy', 'no-referrer')->header('X-Robots-Tag', 'noindex, nofollow');
    }
}
