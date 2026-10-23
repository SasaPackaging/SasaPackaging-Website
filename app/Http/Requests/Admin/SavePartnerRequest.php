<?php

namespace App\Http\Requests\Admin;

use App\Models\Partner;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SavePartnerRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $image = config('partners.image');
        $item  = $this->route('id') ? Partner::find($this->route('id')) : null;
        // a logo is needed for a new partner; when editing, keeping the old one is fine
        $needsLogo = !$item || empty($item->image);

        return [
            'name'       => 'required|string|min:2|max:150',
            'slug'       => 'nullable|url|max:255',
            'status'     => ['required', Rule::in(['Yes', 'No'])],
            'sort_order' => 'nullable|integer|min:0|max:100000',
            'image'      => [$needsLogo ? 'required' : 'nullable', 'file', 'mimes:' . implode(',', $image['extensions']), 'max:' . $image['max_kb']],
        ];
    }

    public function attributes()
    {
        return ['name' => 'company name', 'slug' => 'website link', 'sort_order' => 'order', 'image' => 'logo'];
    }
}
