<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\GuardsActions;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SaveReviewRequest;
use App\Models\Review;
use App\Services\Reviews\ReviewService;
use App\Support\Sanitize;
use Illuminate\Http\Request;

/**
 * Admin > Reviews: every review is kept here; the admin chooses which ones show on the website.
 */
class ReviewController extends Controller
{
    use GuardsActions;

    protected $service;

    public function __construct(ReviewService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $query  = Review::query();
        $state  = Sanitize::query($request->query('state'), 10);
        $search = Sanitize::query($request->query('q'));

        if ($state === 'shown') {
            $query->where('is_visible', true);
        } elseif ($state === 'hidden') {
            $query->where('is_visible', false);
        }
        if ($search !== '') {
            $like = Sanitize::like($search);
            $query->where(function ($q) use ($like) {
                $q->where('name', 'like', $like)->orWhere('company_role', 'like', $like)->orWhere('comment', 'like', $like);
            });
        }

        $site = Review::forSite();

        return view('admin.reviews.index', [
            'items'  => $query->orderByDesc('id')->paginate(config('reviews.admin_per_page'))->appends(['state' => $state, 'q' => $search]),
            'state'  => $state,
            'search' => $search,
            'total'  => Review::count(),
            'shown'  => count($site['items']),
            'average' => $site['average'],
            'allAverage' => round((float) Review::avg('rating'), 1),
        ]);
    }

    public function create()
    {
        return view('admin.reviews.form', ['item' => new Review(['rating' => 5, 'is_visible' => false, 'sort_order' => 0])]);
    }

    public function store(SaveReviewRequest $request)
    {
        return $this->attempt(function () use ($request) {
            $this->service->saveReview(new Review(), $request->validated());

            return redirect()->route('admin.reviews.index')->with('success', 'Review added.');
        }, 'Could not save the review. Please try again.');
    }

    public function edit($id)
    {
        return view('admin.reviews.form', ['item' => Review::with('invite')->findOrFail($id)]);
    }

    public function update(SaveReviewRequest $request, $id)
    {
        $item = Review::findOrFail($id);

        return $this->attempt(function () use ($item, $request) {
            $this->service->saveReview($item, $request->validated());

            return redirect()->route('admin.reviews.edit', $item->id)->with('success', 'Saved.');
        }, 'Could not save. Nothing was changed. Please try again.');
    }

    public function destroy($id)
    {
        $item = Review::findOrFail($id);

        return $this->attempt(function () use ($item) {
            $item->delete();

            return redirect()->route('admin.reviews.index')->with('success', 'Review deleted.');
        }, 'Could not delete it. Please try again.', false);
    }

    /** Show on / hide from the website. */
    public function toggle($id)
    {
        $item = Review::findOrFail($id);

        return $this->attempt(function () use ($item) {
            $item->is_visible = !$item->is_visible;
            $item->save();

            return back()->with('success', $item->is_visible ? 'Now shown on the website.' : 'Hidden from the website (kept here).');
        }, 'Could not change it. Please try again.', false);
    }
}
