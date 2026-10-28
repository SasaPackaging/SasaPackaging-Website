<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * One product of the Shop Now page (table: shop_products).
 */
class ShopProduct extends Model
{
    protected $table = 'shop_products';

    protected $fillable = [
        'name', 'slug', 'category', 'summary', 'description', 'price', 'min_order',
        'images', 'cover_ratio', 'is_featured', 'is_active', 'sort_order',
    ];

    protected $casts = [
        'images'      => 'array',
        'price'       => 'decimal:2',
        'cover_ratio' => 'float',
        'is_featured' => 'boolean',
        'is_active'   => 'boolean',
        'sort_order'  => 'integer',
    ];

    /** Products the public site may show. */
    public function scopeLive($query)
    {
        return $query->where('is_active', true);
    }

    /** Admin-chosen order first, newest first inside the same order number. */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderByDesc('id');
    }

    /**
     * The ONE place that turns a stored file name into a web address.
     * (When pictures move to Cloudinary later, only this method has to change.)
     */
    public static function imageUrl($file)
    {
        return url('/public/' . trim(config('shop.image.dir'), '/') . '/' . rawurlencode($file));
    }

    public function getImageUrlsAttribute()
    {
        return array_values(array_map([static::class, 'imageUrl'], (array) $this->images));
    }

    public function getImageUrlAttribute()
    {
        $urls = $this->image_urls;

        return $urls ? $urls[0] : null;
    }

    public function getUrlAttribute()
    {
        return url(config('shop.product_url') . '/' . $this->slug);
    }

    public function getPriceLabelAttribute()
    {
        if ($this->price === null || (float) $this->price <= 0) {
            return config('shop.page.no_price');
        }

        $amount = (float) $this->price;
        $text   = number_format($amount, floor($amount) == $amount ? 0 : 2);

        return trim(config('shop.currency') . ' ' . $text);
    }

    public function getHasPriceAttribute()
    {
        return $this->price !== null && (float) $this->price > 0;
    }

    /** "Order Now" address: WhatsApp chat with the product name and page link already typed. */
    public function getOrderUrlAttribute()
    {
        $message = strtr(config('shop.whatsapp_message'), [':name' => $this->name, ':url' => $this->url]);

        return 'https://wa.me/' . preg_replace('/\D+/', '', (string) config('shop.whatsapp')) . '?text=' . rawurlencode($message);
    }
}
