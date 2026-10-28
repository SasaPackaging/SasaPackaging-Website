<?php

namespace App\Http\Requests\Admin;

use App\Support\Sanitize;
use Illuminate\Foundation\Http\FormRequest;

class SendReplyRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    /** The subject is one line only (a line break in an email subject can be abused to inject extra mail headers). */
    protected function prepareForValidation()
    {
        $this->merge([
            'subject' => Sanitize::text($this->input('subject')),
            'body'    => Sanitize::text($this->input('body'), true),
        ]);
    }

    public function rules()
    {
        return [
            'subject' => 'required|string|min:2|max:190',
            'body'    => 'required|string|min:2|max:10000',
        ];
    }
}
