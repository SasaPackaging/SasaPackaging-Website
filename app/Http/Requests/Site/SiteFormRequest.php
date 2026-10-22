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

    /** Person's name: letters (any language), spaces, . ' - only. */
    protected function nameRules($max = 120)
    {
        return ['required', 'string', 'min:2', 'max:' . $max, "regex:/^[\\pL\\pM][\\pL\\pM\\s.'\\-]*$/u"];
    }

    /** E-mail: must be a real-looking address (name@domain.com). */
    protected function emailRules()
    {
        return ['required', 'email:rfc,filter', 'max:150'];
    }

    /** Phone: digits with + - ( ) . and spaces allowed, 7 to 15 digits in total. */
    protected function phoneRules()
    {
        return ['required', 'string', 'max:40', 'regex:/^\+?[0-9\s().\-]+$/', function ($attribute, $value, $fail) {
            $digits = strlen(preg_replace('/\D/', '', (string) $value));
            if ($digits < 7 || $digits > 15) {
                $fail(__('inbox.error.phone'));
            }
        }];
    }

    public function messages()
    {
        return [
            'regex' => __('inbox.error.format'),
        ];
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
