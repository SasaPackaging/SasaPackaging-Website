<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\GuardsActions;
use App\Http\Controllers\Controller;
use App\Services\Content\RecordEditor;
use Illuminate\Http\Request;

/**
 * Home slider, Mission & values panels and Inner pages: list + edit rows of the website's own tables.
 * What is editable is described in config/admin_content.php (tables).
 */
class RecordController extends Controller
{
    use GuardsActions;

    protected $editor;

    public function __construct(RecordEditor $editor)
    {
        $this->editor = $editor;
    }

    public function index($table)
    {
        $def = $this->editor->find($table) ?: abort(404);

        return view('admin.records.index', ['def' => $def, 'rows' => $this->editor->paginate($def)]);
    }

    public function create($table)
    {
        $def = $this->editor->find($table) ?: abort(404);
        abort_unless(!empty($def['creatable']), 404);

        return view('admin.records.form', ['def' => $def, 'row' => null]);
    }

    public function store(Request $request, $table)
    {
        $def = $this->editor->find($table) ?: abort(404);
        abort_unless(!empty($def['creatable']), 404);

        $request->validate($this->editor->rules($def));

        return $this->attempt(function () use ($def, $request, $table) {
            $this->editor->create($def, $this->editor->values($def, $request->all()));

            return redirect()->route('admin.records.index', $table)->with('success', 'Added.');
        }, 'Could not add it. Please ask IT support (the table needs extra required columns).');
    }

    public function edit($table, $id)
    {
        $def = $this->editor->find($table) ?: abort(404);

        return view('admin.records.form', ['def' => $def, 'row' => $this->editor->row($def, $id) ?: abort(404)]);
    }

    public function destroy($table, $id)
    {
        $def = $this->editor->find($table) ?: abort(404);
        abort_unless(!empty($def['deletable']), 404);

        return $this->attempt(function () use ($def, $id, $table) {
            $this->editor->delete($def, $id);

            return redirect()->route('admin.records.index', $table)->with('success', 'Deleted.');
        }, 'Could not delete it. Please try again.', false);
    }

    public function update(Request $request, $table, $id)
    {
        $def = $this->editor->find($table) ?: abort(404);
        $this->editor->row($def, $id) ?: abort(404);

        $request->validate($this->editor->rules($def));
        return $this->attempt(function () use ($def, $id, $request, $table) {
            $this->editor->update($def, $id, $this->editor->values($def, $request->all()));

            return redirect()->route('admin.records.edit', [$table, $id])->with('success', 'Saved. The website shows the change now.');
        }, 'Could not save. Please try again.');
    }
}
