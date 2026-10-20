<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Add a new admin / edit an existing one (password is optional when editing).
 */
class SaveAdminUserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $id = $this->route('id'); // null when adding

        return [
            'name'     => 'required|string|max:120',
            'email'    => ['required', 'email', 'max:150', Rule::unique('admins', 'email')->ignore($id)],
            'role'     => ['required', Rule::in(array_keys(config('admin.roles')))],
            'password' => [$id ? 'nullable' : 'required', 'string', 'min:8', 'max:100'],
        ];
    }
}
