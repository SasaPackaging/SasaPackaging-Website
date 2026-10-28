<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SaveMachineRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $image = config('machines.image');

        return [
            'name'         => 'required|string|min:2|max:160',
            'description'  => 'nullable|string|max:1500',
            'sort_order'   => 'nullable|integer|min:0|max:100000',
            'is_active'    => 'nullable|boolean',
            'image'        => 'nullable|file|mimes:' . implode(',', $image['extensions']) . '|max:' . $image['max_kb'],
            'remove_image' => 'nullable|boolean',
        ];
    }

    public function attributes()
    {
        return ['sort_order' => 'order', 'image' => 'picture'];
    }
}
