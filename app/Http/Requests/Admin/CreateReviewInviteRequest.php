<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CreateReviewInviteRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name'       => 'required|string|min:2|max:120',
            'email'      => 'required|email:rfc|max:150',
            'company'    => 'nullable|string|max:150',
            'days'       => 'nullable|integer|min:1|max:180',
            'send_email' => 'nullable|boolean',
        ];
    }

    public function attributes()
    {
        return ['days' => 'valid for (days)'];
    }
}
