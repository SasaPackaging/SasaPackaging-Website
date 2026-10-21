<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveNewsEventRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $image = config('news.image');

        return [
            'type'         => ['required', Rule::in(array_keys(config('news.types')))],
            'title'        => 'required|string|max:200',
            'summary'      => 'nullable|string|max:400',
            'body'         => 'nullable|string|max:200000',
            'event_date'   => 'nullable|required_if:type,event|date',
            'location'     => 'nullable|string|max:150',
            'published_at' => 'required|date',
            'is_published' => 'nullable|boolean',
            'video_url'    => 'nullable|url|max:300',
            'images'          => 'nullable|array|max:' . $image['max_count'],
            'images.*'        => 'file|mimes:' . implode(',', $image['extensions']) . '|max:' . $image['max_kb'],
            'remove_images'   => 'nullable|array',
            'remove_images.*' => 'string|max:190',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($v) {
            // total pictures (kept + new) must stay within the limit
            $item  = $this->route('id') ? \App\Models\NewsEvent::find($this->route('id')) : null;
            $keep  = $item ? count(array_diff((array) $item->images, (array) $this->input('remove_images', []))) : 0;
            $total = $keep + count((array) $this->file('images'));
            if ($total > config('news.image.max_count')) {
                $v->errors()->add('images', 'You can keep at most ' . config('news.image.max_count') . ' pictures. Remove some first.');
            }

            $url = trim((string) $this->input('video_url'));
            if ($url === '' || $v->errors()->has('video_url')) {
                return;
            }
            $host = strtolower((string) parse_url($url, PHP_URL_HOST));
            $ok   = in_array($host, config('news.video_hosts'), true) || preg_match('~\.(mp4|webm)(\?.*)?$~i', $url);
            if (!$ok) {
                $v->errors()->add('video_url', 'Use a YouTube or Vimeo link, or a direct .mp4 / .webm link.');
            }
        });
    }

    public function attributes()
    {
        return ['event_date' => 'event date', 'published_at' => 'publish date', 'video_url' => 'video link', 'images.*' => 'picture'];
    }
}
