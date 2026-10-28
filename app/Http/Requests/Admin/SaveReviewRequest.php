<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SaveReviewRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name'         => 'required|string|min:2|max:120',
            'company_role' => 'nullable|string|max:150',
            'rating'       => 'required|integer|min:1|max:5',
            'comment'      => 'required|string|min:' . config('reviews.comment_min') . '|max:' . config('reviews.comment_max'),
            'sort_order'   => 'nullable|integer|min:0|max:100000',
            'is_visible'   => 'nullable|boolean',
        ];
    }

    public function attributes()
    {
        return ['company_role' => 'company / role', 'sort_order' => 'order'];
    }
}
