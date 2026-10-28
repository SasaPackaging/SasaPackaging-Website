<?php

namespace App\Http\Requests\Site;

use App\Support\Sanitize;
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

    /** Form fields that may contain line breaks (message boxes). Every other text field is one single line. */
    protected function multilineFields()
    {
        return [];
    }

    /**
     * Runs BEFORE validation. Removes invisible control characters and line breaks from one-line fields
     * (a line break inside a name / subject / email is how "header injection" in emails is attempted),
     * and turns odd values such as  name[]=x  into plain text so they fail validation instead of crashing.
     */
    protected function prepareForValidation()
    {
        $input = $this->except($this->fileFields());

        // None of the public forms (contact, quote) has a list field, so  name[]=x  can only be an attack / a bug
        foreach ($input as $key => $value) {
            if (is_array($value)) {
                $input[$key] = '';
            }
        }

        $this->merge(Sanitize::deep($input, $this->multilineFields()));
    }

    /** Names of the file inputs (they are left untouched). */
    protected function fileFields()
    {
        return [];
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
            $digits = strlen(preg_replace('/\D/', '', is_scalar($value) ? (string) $value : ''));
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

    /**
     * Hidden "website" box that real visitors never see. Bots fill it in.
     * The form then pretends to succeed but nothing is saved or emailed.
     */
    public function isSpam()
    {
        return $this->filled('website');
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
