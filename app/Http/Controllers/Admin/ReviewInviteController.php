<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\GuardsActions;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CreateReviewInviteRequest;
use App\Models\ReviewInvite;
use App\Services\Reviews\ReviewService;

/**
 * Admin > Review invites: send a private, single-use feedback link to a partner / collaborator.
 */
class ReviewInviteController extends Controller
{
    use GuardsActions;

    protected $service;

    public function __construct(ReviewService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return view('admin.review-invites.index', [
            'items' => ReviewInvite::with('review')->orderByDesc('id')->paginate(config('reviews.admin_per_page')),
        ]);
    }

    public function store(CreateReviewInviteRequest $request)
    {
        $invite = $this->service->createInvite($request->validated());

        if (!$request->boolean('send_email')) {
            return redirect()->route('admin.review-invites.index')->with('success', 'Invite created. Use “Copy link” to send it by WhatsApp or any other way.');
        }

        try {
            $this->service->sendInvite($invite);
        } catch (\Throwable $e) {
            report($e);

            return redirect()->route('admin.review-invites.index')
                ->with('error', 'The invite was created but the email could not be sent (check the mail settings). Use “Copy link” instead.');
        }

        return redirect()->route('admin.review-invites.index')->with('success', 'Invite created and emailed to ' . $invite->email . '.');
    }

    public function resend($id)
    {
        $invite = ReviewInvite::findOrFail($id);

        if (!$invite->is_open) {
            return back()->with('error', 'This invite is no longer active, so it cannot be sent again.');
        }

        try {
            $this->service->sendInvite($invite);
        } catch (\Throwable $e) {
            report($e);

            return back()->with('error', 'The email could not be sent (check the mail settings). Use “Copy link” instead.');
        }

        return back()->with('success', 'Email sent again to ' . $invite->email . '.');
    }

    public function revoke($id)
    {
        $invite = ReviewInvite::findOrFail($id);

        if ($invite->is_open) {
            $invite->forceFill(['revoked_at' => now()])->save();
        }

        return back()->with('success', 'The link no longer works.');
    }

    public function destroy($id)
    {
        ReviewInvite::findOrFail($id)->delete();   // a review already received from it is kept

        return back()->with('success', 'Invite deleted.');
    }
}
