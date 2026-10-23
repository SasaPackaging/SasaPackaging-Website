<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\GuardsActions;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SavePartnerRequest;
use App\Models\Partner;
use App\Services\Partners\PartnerService;
use Illuminate\Support\Facades\Schema;

/**
 * Admin > Partners: the logos that slide across the home page.
 */
class PartnerController extends Controller
{
    use GuardsActions;

    protected $service;

    public function __construct(PartnerService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $query = Partner::query();
        if (Schema::hasColumn(config('partners.table'), 'sort_order')) {
            $query->orderBy('sort_order');
        }

        return view('admin.partners.index', ['items' => $query->orderBy('id')->paginate(config('admin.per_page', 20))]);
    }

    public function create()
    {
        return view('admin.partners.form', ['item' => new Partner(['status' => 'Yes'])]);
    }

    public function store(SavePartnerRequest $request)
    {
        return $this->attempt(function () use ($request) {
            $this->service->save(new Partner(), $request->validated(), $request->file('image'));

            return redirect()->route('admin.partners.index')->with('success', 'Partner added. It slides on the home page now.');
        }, 'Could not add the partner. Nothing was saved. Please try again (or use a smaller logo).');
    }

    public function edit($id)
    {
        return view('admin.partners.form', ['item' => Partner::findOrFail($id)]);
    }

    public function update(SavePartnerRequest $request, $id)
    {
        $item = Partner::findOrFail($id);

        return $this->attempt(function () use ($request, $item) {
            $this->service->save($item, $request->validated(), $request->file('image'));

            return redirect()->route('admin.partners.edit', $item->id)->with('success', 'Saved.');
        }, 'Could not save. Nothing was changed. Please try again (or use a smaller logo).');
    }

    public function destroy($id)
    {
        $item = Partner::findOrFail($id);

        return $this->attempt(function () use ($item) {
            $this->service->delete($item);

            return redirect()->route('admin.partners.index')->with('success', 'Partner removed.');
        }, 'Could not remove it. Please try again.', false);
    }
}
