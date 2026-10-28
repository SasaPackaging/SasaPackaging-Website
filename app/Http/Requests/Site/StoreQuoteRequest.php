<?php

namespace App\Http\Requests\Site;

use App\Services\Quote\QuoteOptionsService;
use App\Support\UploadGuard;
use Illuminate\Validation\Rule;

/**
 * Get a Quote form  (field names are the ones used by quote.blade.php).
 * Every allowed value comes from config/quote.php or the products / services tables.
 */
class StoreQuoteRequest extends SiteFormRequest
{
    protected function multilineFields()
    {
        return ['q_message'];
    }

    protected function fileFields()
    {
        return ['q_file'];
    }

    protected function prepareForValidation()
    {
        // remove control characters / line breaks from one-line fields first
        parent::prepareForValidation();

        // "1,000" or "1 000" is a fine quantity; "0.5" / "1,5" is a fine size.
        $merge = [];
        foreach (['q_quantity_1', 'q_quantity_2'] as $key) {
            if ($this->has($key)) {
                $merge[$key] = preg_replace('/[\s,]/', '', (string) $this->input($key));
            }
        }
        foreach (['q_length', 'q_width', 'q_height'] as $key) {
            if ($this->has($key)) {
                $merge[$key] = str_replace(',', '.', trim((string) $this->input($key)));
            }
        }

        $this->merge($merge);
    }

    public function rules()
    {
        $options = app(QuoteOptionsService::class);
        $file    = config('quote.file');

        $size = 'required|numeric|min:0.01|max:100000';

        return [
            'q_product_id'   => ['nullable', 'integer', Rule::in(array_keys($options->products()))],
            'q_service_id'   => ['nullable', 'integer', Rule::in(array_keys($options->services()))],

            'q_length'       => $size,
            'q_width'        => $size,
            'q_height'       => $size,
            'q_unit'         => ['required', Rule::in(array_keys(config('quote.units')))],

            'q_produt_name'  => 'required|string|max:190',
            'q_stock'        => ['required', Rule::in(array_keys(config('quote.stocks')))],
            'q_coating'      => ['required', Rule::in(array_keys(config('quote.coatings')))],
            'q_add_ons'      => ['required', Rule::in(array_keys(config('quote.add_ons')))],
            'q_colours'      => ['required', Rule::in(array_keys(config('quote.colours')))],
            'q_packing'      => ['nullable', Rule::in(array_keys(config('quote.packings')))],

            'q_quantity_1'   => 'required|integer|min:1|max:100000000',
            'q_quantity_2'   => 'required|integer|min:1|max:100000000',

            'q_file'         => [
                'bail',
                'required',
                'file',
                'max:' . $file['max_kb'],
                function ($attribute, $value, $fail) use ($file) {
                    // 1. a real, fully uploaded file (not text typed into the field, not a broken upload)
                    if (!UploadGuard::isUsable($value)) {
                        return $fail(__('inbox.error.file_upload'));
                    }

                    // 2. whitelist by extension (ai / cdr have no reliable mime type)
                    $ext = strtolower($value->getClientOriginalExtension());
                    if (!in_array($ext, $file['extensions'], true)) {
                        return $fail(__('inbox.error.file_type', ['types' => implode(', ', $file['extensions'])]));
                    }

                    // 3. the real content must fit the extension, and must not hide script code
                    if (!UploadGuard::mimeMatchesExtension($value, $ext) || UploadGuard::looksLikeScript($value)) {
                        return $fail(__('inbox.error.file_content'));
                    }
                },
            ],

            'q_full_name'    => $this->nameRules(),
            'q_phone'        => $this->phoneRules(),
            'q_email'        => $this->emailRules(),
            'q_company'      => 'required|string|min:2|max:150',
            'q_message'      => 'nullable|string|max:3000',
            'website'        => 'nullable|string|max:255', // honeypot, see isSpam()
        ];
    }

    public function attributes()
    {
        return [
            'q_product_id'  => 'product',
            'q_service_id'  => 'service',
            'q_length'      => 'length',
            'q_width'       => 'width',
            'q_height'      => 'height',
            'q_unit'        => 'unit',
            'q_produt_name' => 'product name',
            'q_stock'       => 'stock',
            'q_coating'     => 'coating',
            'q_add_ons'     => 'add ons',
            'q_colours'     => 'colours',
            'q_packing'     => 'packing',
            'q_quantity_1'  => 'quantity 1',
            'q_quantity_2'  => 'quantity 2',
            'q_file'        => 'file',
            'q_full_name'   => 'name',
            'q_phone'       => 'phone number',
            'q_email'       => 'email',
            'q_company'     => 'company name',
            'q_message'     => 'message',
        ];
    }

    /** Form field names -> database columns. Only these fields are ever saved. */
    public function payload()
    {
        return [
            'product_id' => $this->filled('q_product_id') ? (int) $this->input('q_product_id') : null,
            'service_id' => $this->filled('q_service_id') ? (int) $this->input('q_service_id') : null,
            'item_name'  => $this->input('q_produt_name'),
            'length'     => $this->input('q_length'),
            'width'      => $this->input('q_width'),
            'height'     => $this->input('q_height'),
            'unit'       => $this->input('q_unit'),
            'stock'      => $this->input('q_stock'),
            'coating'    => $this->input('q_coating'),
            'add_ons'    => $this->input('q_add_ons'),
            'colours'    => $this->input('q_colours'),
            'packing'    => $this->input('q_packing'),
            'quantity_1' => $this->input('q_quantity_1'),
            'quantity_2' => $this->input('q_quantity_2'),
            'full_name'  => $this->input('q_full_name'),
            'phone'      => $this->input('q_phone'),
            'email'      => $this->input('q_email'),
            'company'    => $this->input('q_company'),
            'message'    => $this->input('q_message'),
        ];
    }
}
