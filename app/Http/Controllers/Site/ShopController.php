<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\ShopProduct;
use App\Support\Sanitize;
use Illuminate\Http\Request;

/**
 * Public Shop Now: the Pinterest-style grid (/shop.html) and one product page (/shop/{slug}).
 */
class ShopController extends Controller
{
    public function index(Request $request)
    {
        $page     = config('shop.page');
        $category = Sanitize::query($request->query('category'), 80);

        $categories = ShopProduct::live()->whereNotNull('category')->where('category', '!=', '')
            ->distinct()->orderBy('category')->pluck('category');

        // an unknown category in the address bar simply shows everything
        if ($category !== '' && !$categories->contains($category)) {
            $category = '';
        }

        $query = ShopProduct::live();
        if ($category !== '') {
            $query->where('category', $category);
        }

        $products = $query->ordered()->paginate(config('shop.per_page'))->appends($category !== '' ? ['category' => $category] : []);

        return view('pages.shop', [
            'products'    => $products,
            'categories'  => $categories,
            'category'    => $category,
            'page'        => $page,
            'keywords'    => '',
            'title'       => $page['title'] . ($category !== '' ? ' - ' . $category : '') . ' | ' . config('site.name'),
            'description' => $page['lead'],
            'og_image'    => optional($products->first())->image_url,
        ]);
    }

    public function show($slug)
    {
        $product = ShopProduct::live()->where('slug', $slug)->firstOrFail();

        $related = ShopProduct::live()->where('id', '!=', $product->id)
            ->when($product->category, function ($q) use ($product) {
                $q->orderByRaw('category = ? DESC', [$product->category]);
            })
            ->ordered()->limit(4)->get();

        return view('pages.shop-product', [
            'product'     => $product,
            'related'     => $related,
            'page'        => config('shop.page'),
            'keywords'    => '',
            'title'       => $product->name . ' | ' . config('shop.page.title') . ' | ' . config('site.name'),
            'description' => (string) ($product->summary ?: strip_tags((string) $product->description)),
            'og_image'    => $product->image_url,
        ]);
    }
}
