<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\GuardsActions;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SendReplyRequest;
use App\Http\Requests\Admin\UpdateInboxStatusRequest;
use App\Services\Inbox\ReplyService;
use App\Support\Sanitize;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Everything an inbox page does (list, open, change status, reply, delete).
 * Contact messages and Quote requests only tell this class WHICH model, route and view folder to use,
 * so a third inbox (for example job applications) can be added with ~10 lines.
 */
abstract class InboxController extends Controller
{
    use GuardsActions;

    /** @return string  model class, e.g. \App\Models\ContactMessage::class */
    abstract protected function model();

    /** @return string  route name prefix, e.g. 'admin.contact-messages' */
    abstract protected function routeBase();

    /** @return string  view folder, e.g. 'admin.contact-messages' */
    abstract protected function viewBase();

    /** @return string  what the admin sees in headings, e.g. 'Contact messages' */
    abstract protected function title();

    public function index(Request $request)
    {
        $model  = $this->model();
        $status = Sanitize::query($request->query('status'), 30); // always plain text, never an array
        $search = Sanitize::query($request->query('q'));

        $items = $model::query()
            ->filterStatus($status)
            ->search($search)
            ->latest('id')
            ->paginate(config('admin.per_page'))
            ->appends($request->query());

        return view($this->viewBase() . '.index', [
            'items'     => $items,
            'counts'    => $model::statusCounts(),
            'status'    => $status,
            'search'    => $search,
            'inbox'     => $this->inboxMeta(),
        ]);
    }

    public function show($id)
    {
        $item = $this->find($id);

        $item->markAsOpened();
        $item->load('replies.admin');

        return view($this->viewBase() . '.show', [
            'item'  => $item,
            'inbox' => $this->inboxMeta(),
        ]);
    }

    public function updateStatus(UpdateInboxStatusRequest $request, $id)
    {
        $item = $this->find($id);
        return $this->attempt(function () use ($item, $request) {
            $item->changeStatus($request->input('status'));

            return back()->with('success', 'Status updated to "' . $item->status_label . '".');
        }, 'Could not change the status. Please try again.', false);
    }

    public function reply(SendReplyRequest $request, $id, ReplyService $replies)
    {
        $item = $this->find($id);

        try {
            $replies->send($item, Auth::guard('admin')->user(), $request->input('subject'), $request->input('body'));
        } catch (\Throwable $e) {
            report($e);

            return back()
                ->withInput()
                ->with('error', 'The reply could not be sent, so nothing was saved. Please check the mail settings and try again.');
        }

        // With the "log" / "array" mail drivers nothing is really delivered, so do not claim it was.
        if (in_array(config('mail.default'), ['log', 'array'], true)) {
            return back()->with('error', 'Reply saved, but NOT delivered: MAIL_MAILER is "' . config('mail.default') . '". Set up SMTP in the .env file to send real emails.');
        }

        return back()->with('success', 'Reply sent to ' . $item->email . ' and marked as replied.');
    }

    public function destroy($id)
    {
        $item = $this->find($id);

        return $this->attempt(function () use ($item) {
            $this->beforeDelete($item);
            $item->delete();

            return redirect()->route($this->routeBase() . '.index')->with('success', 'Deleted.');
        }, 'Could not delete it. Please try again.', false);
    }

    /** Hook for children that own extra things (e.g. a file) which must go with the record. */
    protected function beforeDelete($item)
    {
    }

    protected function find($id)
    {
        $model = $this->model();

        return $model::findOrFail($id);
    }

    /** Names the shared inbox views need (routes + heading). */
    protected function inboxMeta()
    {
        return [
            'title'      => $this->title(),
            'route'      => $this->routeBase(),
            'statuses'   => config('admin.inbox.statuses'),
        ];
    }
}
