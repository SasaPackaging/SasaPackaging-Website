<?php

namespace App\Http\Requests\Site;

use App\Support\Sanitize;
use Illuminate\Foundation\Http\FormRequest;

/** The public feedback form: rating, comment and (optionally) the person's company / role. */
class SubmitFeedbackRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'company_role' => Sanitize::text($this->input('company_role')),
            'comment'      => Sanitize::text($this->input('comment'), true),
            'rating'       => Sanitize::text($this->input('rating')),
            'website'      => Sanitize::text($this->input('website')),   // hidden "honeypot" field: only bots fill it
        ]);
    }

    public function rules()
    {
        return [
            'rating'       => 'required|integer|min:1|max:5',
            'comment'      => 'required|string|min:' . config('reviews.comment_min') . '|max:' . config('reviews.comment_max'),
            'company_role' => 'nullable|string|max:150',
            'website'      => 'nullable|string|max:200',
        ];
    }

    public function messages()
    {
        return ['rating.required' => 'Please choose a star rating.', 'rating.*' => 'Please choose a star rating from 1 to 5.'];
    }

    public function attributes()
    {
        return ['comment' => 'feedback', 'company_role' => 'company / role'];
    }
}
