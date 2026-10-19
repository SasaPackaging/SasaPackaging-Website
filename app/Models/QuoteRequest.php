<?php

namespace App\Models;

use App\Models\Concerns\InboxItem;
use Illuminate\Database\Eloquent\Model;

/**
 * A request sent from the website's "Get a Quote" form (table: quote_requests).
 */
class QuoteRequest extends Model
{
    use InboxItem;

    protected $table = 'quote_requests';

    protected $fillable = [
        'reference',
        'product_id',
        'product_label',
        'service_id',
        'service_label',
        'item_name',
        'length',
        'width',
        'height',
        'unit',
        'stock',
        'coating',
        'add_ons',
        'colours',
        'packing',
        'quantity_1',
        'quantity_2',
        'file_path',
        'file_original_name',
        'file_size',
        'full_name',
        'phone',
        'email',
        'company',
        'message',
        'status',
        'ip_address',
        'user_agent',
        'mail_sent_at',
        'read_at',
        'replied_at',
    ];

    protected $casts = [
        'length'       => 'float',
        'width'        => 'float',
        'height'       => 'float',
        'quantity_1'   => 'integer',
        'quantity_2'   => 'integer',
        'mail_sent_at' => 'datetime',
        'read_at'      => 'datetime',
        'replied_at'   => 'datetime',
    ];

    public static function searchableColumns()
    {
        return ['reference', 'full_name', 'email', 'phone', 'company', 'item_name', 'product_label', 'service_label', 'message'];
    }

    public function hasFile()
    {
        return !empty($this->file_path);
    }

    /** "20 × 10 × 5 CM" */
    public function getSizeTextAttribute()
    {
        $n = function ($v) {
            return rtrim(rtrim(number_format((float) $v, 2, '.', ''), '0'), '.');
        };

        return $n($this->length) . ' × ' . $n($this->width) . ' × ' . $n($this->height) . ' ' . $this->unit;
    }
}
