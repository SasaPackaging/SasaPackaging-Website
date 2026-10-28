<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\GuardsActions;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SaveShopProductRequest;
use App\Models\ShopProduct;
use App\Services\Shop\ShopProductService;
use App\Support\Sanitize;
use Illuminate\Http\Request;

/**
 * Admin > Shop products (add, edit, hide, delete) for the Shop Now page.
 */
class ShopProductController extends Controller
{
    use GuardsActions;

    protected $service;

    public function __construct(ShopProductService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $query  = ShopProduct::query();
        $search = Sanitize::query($request->query('q'));
        $cat    = Sanitize::query($request->query('category'), 80);
        $state  = Sanitize::query($request->query('state'), 10);

        if ($search !== '') {
            $query->where('name', 'like', Sanitize::like($search));
        }
        if ($cat !== '') {
            $query->where('category', $cat);
        }
        if ($state === 'live') {
            $query->where('is_active', true);
        } elseif ($state === 'hidden') {
            $query->where('is_active', false);
        }

        return view('admin.shop.index', [
            'items'      => $query->ordered()->paginate(config('shop.admin_per_page'))->appends(['q' => $search, 'category' => $cat, 'state' => $state]),
            'categories' => $this->categories(),
            'search'     => $search,
            'category'   => $cat,
            'state'      => $state,
        ]);
    }

    public function create()
    {
        return view('admin.shop.form', [
            'item'       => new ShopProduct(['is_active' => true, 'sort_order' => 0]),
            'categories' => $this->categories(),
        ]);
    }

    public function store(SaveShopProductRequest $request)
    {
        return $this->attempt(function () use ($request) {
            $item = $this->service->save(new ShopProduct(), $request->validated(), (array) $request->file('images'));

            return redirect()->route('admin.shop-products.edit', $item->id)
                ->with('success', 'Product added. ' . ($item->is_active ? 'It is live on the Shop page.' : 'It is hidden until you switch it on.'));
        }, 'Could not save. Nothing was added. Please try again (or use a smaller picture).');
    }

    public function edit($id)
    {
        return view('admin.shop.form', ['item' => ShopProduct::findOrFail($id), 'categories' => $this->categories()]);
    }

    public function update(SaveShopProductRequest $request, $id)
    {
        $item = ShopProduct::findOrFail($id);

        return $this->attempt(function () use ($item, $request) {
            $this->service->save($item, $request->validated(), (array) $request->file('images'), (array) $request->input('remove_images', []));

            return redirect()->route('admin.shop-products.edit', $item->id)->with('success', 'Saved.');
        }, 'Could not save. Nothing was changed. Please try again (or use a smaller picture).');
    }

    public function destroy($id)
    {
        $item = ShopProduct::findOrFail($id);

        return $this->attempt(function () use ($item) {
            $this->service->delete($item);

            return redirect()->route('admin.shop-products.index')->with('success', 'Product deleted.');
        }, 'Could not delete it. Please try again.', false);
    }

    /** Quick show / hide from the list. */
    public function toggle($id)
    {
        $item = ShopProduct::findOrFail($id);

        return $this->attempt(function () use ($item) {
            $item->is_active = !$item->is_active;
            $item->save();

            return back()->with('success', $item->is_active ? 'Now live on the Shop page.' : 'Hidden from the Shop page.');
        }, 'Could not change it. Please try again.', false);
    }

    /** Categories already used (suggestions in the form, filter in the list). */
    protected function categories()
    {
        return ShopProduct::whereNotNull('category')->where('category', '!=', '')->distinct()->orderBy('category')->pluck('category');
    }
}
