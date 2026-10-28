<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\GuardsActions;
use App\Http\Controllers\Controller;
use App\Services\Catalog\CatalogEditor;
use App\Support\Sanitize;
use Illuminate\Http\Request;

/**
 * Shared screens for the lists kept in config/catalog.php (Services, Products):
 * list + search, add, edit, hide, delete, with pictures.
 * A child class only says WHICH list it is, so a third list is a few lines.
 */
abstract class CatalogController extends Controller
{
    use GuardsActions;

    protected $editor;

    public function __construct(CatalogEditor $editor)
    {
        $this->editor = $editor;
    }

    /** @return string  key in config/catalog.php, e.g. 'services' */
    abstract protected function type();

    protected function def()
    {
        return $this->editor->find($this->type()) ?: abort(404);
    }

    public function index(Request $request)
    {
        $def = $this->def();

        return view('admin.catalog.index', ['def' => $def, 'rows' => $this->editor->paginate($def, Sanitize::query($request->query('q')))]);
    }

    public function create()
    {
        return view('admin.catalog.form', ['def' => $this->def(), 'row' => null]);
    }

    public function store(Request $request)
    {
        $def = $this->def();
        $request->validate($this->editor->rules($def), [], $this->editor->attributes($def));

        return $this->attempt(function () use ($def, $request) {
            $this->editor->save($def, null, $request->all(), $this->files($def, $request));

            return redirect()->route($def['route'] . '.index')->with('success', 'Added.');
        }, 'Could not add it. Nothing was saved. Please try again (or use a smaller picture).');
    }

    public function edit($id)
    {
        $def = $this->def();

        return view('admin.catalog.form', ['def' => $def, 'row' => $this->editor->row($def, $id) ?: abort(404)]);
    }

    public function update(Request $request, $id)
    {
        $def = $this->def();
        $row = $this->editor->row($def, $id) ?: abort(404);
        $request->validate($this->editor->rules($def), [], $this->editor->attributes($def));

        return $this->attempt(function () use ($def, $row, $request) {
            $this->editor->save($def, $row, $request->all(), $this->files($def, $request));

            return redirect()->route($def['route'] . '.edit', $row->id)->with('success', 'Saved. The website shows the change now.');
        }, 'Could not save. Nothing was changed. Please try again (or use a smaller picture).');
    }

    public function destroy($id)
    {
        $def = $this->def();
        $row = $this->editor->row($def, $id) ?: abort(404);

        return $this->attempt(function () use ($def, $row) {
            $this->editor->delete($def, $row);

            return redirect()->route($def['route'] . '.index')->with('success', 'Deleted.');
        }, 'Could not delete it. Please try again.', false);
    }

    /** uploaded pictures: field name => file */
    protected function files(array $def, Request $request)
    {
        $files = [];
        foreach ($def['fields'] as $f) {
            if ($f['type'] === 'image' && $request->hasFile($f['name'])) {
                $files[$f['name']] = $request->file($f['name']);
            }
        }

        return $files;
    }
}
