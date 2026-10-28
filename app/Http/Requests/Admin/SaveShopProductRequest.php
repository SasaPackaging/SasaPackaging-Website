<?php

namespace App\Http\Requests\Admin;

use App\Models\ShopProduct;
use Illuminate\Foundation\Http\FormRequest;

class SaveShopProductRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $image = config('shop.image');

        return [
            'name'            => 'required|string|min:2|max:160',
            'category'        => 'nullable|string|max:80',
            'summary'         => 'nullable|string|max:220',
            'description'     => 'nullable|string|max:200000',
            'price'           => 'nullable|numeric|min:0|max:9999999999',
            'min_order'       => 'nullable|string|max:60',
            'sort_order'      => 'nullable|integer|min:0|max:100000',
            'is_active'       => 'nullable|boolean',
            'is_featured'     => 'nullable|boolean',
            'images'          => 'nullable|array|max:' . $image['max_count'],
            'images.*'        => 'file|mimes:' . implode(',', $image['extensions']) . '|max:' . $image['max_kb'],
            'remove_images'   => 'nullable|array',
            'remove_images.*' => 'string|max:190',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($v) {
            $item = $this->route('id') ? ShopProduct::find($this->route('id')) : null;
            $keep = $item ? count(array_diff((array) $item->images, (array) $this->input('remove_images', []))) : 0;
            $new  = count((array) $this->file('images'));

            // a product without a picture would be an empty tile in the grid
            if ($keep + $new < 1) {
                $v->errors()->add('images', 'Add at least one picture. The picture is the main part of the shop card.');
            }
            if ($keep + $new > config('shop.image.max_count')) {
                $v->errors()->add('images', 'You can keep at most ' . config('shop.image.max_count') . ' pictures. Remove some first.');
            }
        });
    }

    public function attributes()
    {
        return ['min_order' => 'minimum order', 'sort_order' => 'order', 'images.*' => 'picture', 'summary' => 'short description'];
    }
}
