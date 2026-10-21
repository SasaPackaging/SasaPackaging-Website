<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Content\RecordEditor;
use Illuminate\Http\Request;

/**
 * Home slider, Mission & values panels and Inner pages: list + edit rows of the website's own tables.
 * What is editable is described in config/admin_content.php (tables).
 */
class RecordController extends Controller
{
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

        try {
            $this->editor->create($def, $this->editor->values($def, $request->all()));
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', 'Could not add it. Please ask IT support (the table needs extra required columns).');
        }

        return redirect()->route('admin.records.index', $table)->with('success', 'Added.');
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

        $this->editor->delete($def, $id);

        return redirect()->route('admin.records.index', $table)->with('success', 'Deleted.');
    }

    public function update(Request $request, $table, $id)
    {
        $def = $this->editor->find($table) ?: abort(404);
        $this->editor->row($def, $id) ?: abort(404);

        $request->validate($this->editor->rules($def));
        $this->editor->update($def, $id, $this->editor->values($def, $request->all()));

        return redirect()->route('admin.records.edit', [$table, $id])->with('success', 'Saved. The website shows the change now.');
    }
}
