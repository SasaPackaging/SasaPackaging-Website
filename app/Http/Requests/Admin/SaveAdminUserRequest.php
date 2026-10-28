<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

/**
 * Add a new admin / edit an existing one (password is optional when editing).
 */
class SaveAdminUserRequest extends FormRequest
{
    public function authorize()
    {
        return true; // the route is already limited to super admins (admin.can:users)
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'name'  => trim(preg_replace('/\s+/u', ' ', (string) (is_scalar($this->input('name')) ? $this->input('name') : ''))),
            'email' => mb_strtolower(trim((string) (is_scalar($this->input('email')) ? $this->input('email') : ''))),
        ]);
    }

    public function rules()
    {
        $id = $this->route('id'); // null when adding

        return [
            'name'     => ['required', 'string', 'min:2', 'max:120'],
            'email'    => ['required', 'string', 'email', 'max:150', Rule::unique('admins', 'email')->ignore($id)],
            'role'     => ['required', Rule::in(array_keys(config('admin.roles')))],
            // a strong password: 10+ characters with upper + lower case letters and a number
            'password' => [$id ? 'nullable' : 'required', 'string', 'max:100', Password::min(10)->mixedCase()->numbers()],
        ];
    }

    public function messages()
    {
        return [
            'password.min' => 'The password must be at least 10 characters.',
        ];
    }
}
