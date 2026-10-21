<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\NewsEvent;
use Illuminate\Http\Request;

/**
 * Public Events page: the latest (or chosen) event on top, earlier events with their pictures and videos below.
 */
class EventsController extends Controller
{
    public function index(Request $request)
    {
        $events = NewsEvent::live()
            ->where('type', 'event')
            ->orderByRaw('COALESCE(event_date, published_at) DESC')
            ->orderByDesc('id')
            ->get();

        // /events.html?event=slug puts that event on top; otherwise the latest one
        $featured = $events->firstWhere('slug', $request->query('event')) ?: $events->first();
        $previous = $featured ? $events->where('id', '!=', $featured->id)->values() : $events;
        $page     = config('news.events_page');

        return view('pages.events', [
            'featured'    => $featured,
            'previous'    => $previous,
            'page'        => $page,
            'keywords'    => '',
            'title'       => ($featured ? $featured->title . ' | ' : '') . $page['title'] . ' | ' . config('site.name'),
            'description' => $featured ? (string) $featured->summary : $page['empty_text'],
            'og_image'    => $featured ? $featured->image_url : '',
        ]);
    }
}
