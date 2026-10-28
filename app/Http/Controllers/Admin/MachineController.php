<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\GuardsActions;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SaveMachineRequest;
use App\Models\Machine;
use App\Services\Machines\MachineService;

/**
 * Admin > Machines (add, edit, hide, delete) for the Machines / Automation page.
 */
class MachineController extends Controller
{
    use GuardsActions;

    protected $service;

    public function __construct(MachineService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return view('admin.machines.index', ['items' => Machine::ordered()->paginate(config('machines.admin_per_page'))]);
    }

    public function create()
    {
        return view('admin.machines.form', ['item' => new Machine(['is_active' => true, 'sort_order' => (int) Machine::max('sort_order') + 1])]);
    }

    public function store(SaveMachineRequest $request)
    {
        return $this->attempt(function () use ($request) {
            $item = $this->service->save(new Machine(), $request->validated(), $request->file('image'));

            return redirect()->route('admin.machines.edit', $item->id)->with('success', 'Machine added.' . ($item->is_active ? ' It is live on the website.' : ' It is hidden until you switch it on.'));
        }, 'Could not save. Nothing was added. Please try again (or use a smaller picture).');
    }

    public function edit($id)
    {
        return view('admin.machines.form', ['item' => Machine::findOrFail($id)]);
    }

    public function update(SaveMachineRequest $request, $id)
    {
        $item = Machine::findOrFail($id);

        return $this->attempt(function () use ($item, $request) {
            $this->service->save($item, $request->validated(), $request->file('image'), (bool) $request->input('remove_image'));

            return redirect()->route('admin.machines.edit', $item->id)->with('success', 'Saved.');
        }, 'Could not save. Nothing was changed. Please try again (or use a smaller picture).');
    }

    public function destroy($id)
    {
        $item = Machine::findOrFail($id);

        return $this->attempt(function () use ($item) {
            $this->service->delete($item);

            return redirect()->route('admin.machines.index')->with('success', 'Machine deleted.');
        }, 'Could not delete it. Please try again.', false);
    }

    public function toggle($id)
    {
        $item = Machine::findOrFail($id);

        return $this->attempt(function () use ($item) {
            $item->is_active = !$item->is_active;
            $item->save();

            return back()->with('success', $item->is_active ? 'Now live on the website.' : 'Hidden from the website.');
        }, 'Could not change it. Please try again.', false);
    }
}
