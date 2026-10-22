<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\GuardsActions;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SaveNewsEventRequest;
use App\Models\NewsEvent;
use App\Services\News\NewsEventService;
use Illuminate\Http\Request;

/**
 * Admin > News & events (add, edit, hide, delete).
 */
class NewsEventController extends Controller
{
    use GuardsActions;

    protected $service;

    public function __construct(NewsEventService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $query = NewsEvent::query();

        if ($request->filled('type') && array_key_exists($request->type, config('news.types'))) {
            $query->where('type', $request->type);
        }
        if ($request->filled('q')) {
            $query->where('title', 'like', '%' . $request->q . '%');
        }

        return view('admin.news.index', [
            'items' => $query->orderByDesc('published_at')->orderByDesc('id')->paginate(config('news.per_page'))->appends($request->only('type', 'q')),
            'types' => config('news.types'),
        ]);
    }

    public function create()
    {
        return view('admin.news.form', ['item' => new NewsEvent(['type' => 'news', 'is_published' => true, 'published_at' => now()]), 'types' => config('news.types')]);
    }

    public function store(SaveNewsEventRequest $request)
    {
        return $this->attempt(function () use ($request) {
            $item = $this->service->save(new NewsEvent(), $request->validated(), (array) $request->file('images'));

            return redirect()->route('admin.news.edit', $item->id)->with('success', 'Added. ' . ($item->is_published ? 'It is live on the website.' : 'It is hidden until you publish it.'));
        }, 'Could not save. Nothing was added. Please try again (or use a smaller picture).');
    }

    public function edit($id)
    {
        return view('admin.news.form', ['item' => NewsEvent::findOrFail($id), 'types' => config('news.types')]);
    }

    public function update(SaveNewsEventRequest $request, $id)
    {
        $item = NewsEvent::findOrFail($id);
        return $this->attempt(function () use ($item, $request) {
            $this->service->save($item, $request->validated(), (array) $request->file('images'), (array) $request->input('remove_images', []));

            return redirect()->route('admin.news.edit', $item->id)->with('success', 'Saved.');
        }, 'Could not save. Nothing was changed. Please try again (or use a smaller picture).');
    }

    public function destroy($id)
    {
        $item = NewsEvent::findOrFail($id);

        return $this->attempt(function () use ($item) {
            $this->service->delete($item);

            return redirect()->route('admin.news.index')->with('success', 'Deleted.');
        }, 'Could not delete it. Please try again.', false);
    }
}
