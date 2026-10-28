<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    /** Emails are not case sensitive; stray spaces must not cause a "wrong password". */
    protected function prepareForValidation()
    {
        $this->merge([
            'email' => mb_strtolower(trim((string) (is_scalar($this->input('email')) ? $this->input('email') : ''))),
        ]);
    }

    public function rules()
    {
        return [
            'email'    => 'required|string|email|max:150',
            'password' => 'required|string|max:200',
        ];
    }
}
