<?php

namespace App\Http\Requests\Site;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

/**
 * Base for the public website forms.
 * The website's JavaScript (sasa-forms.js) expects  { status, message }  so a failed
 * validation answers with the first error in exactly that shape.
 */
abstract class SiteFormRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status'  => 'error',
            'message' => $validator->errors()->first(),
            'errors'  => $validator->errors(),
        ], 422));
    }

    /** Meta stored with every submission (helps spot spam). */
    public function meta()
    {
        return [
            'ip_address' => $this->ip(),
            'user_agent' => mb_substr((string) $this->userAgent(), 0, 500),
        ];
    }
}
