<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Content\GroupEditor;
use Illuminate\Http\Request;

/**
 * Landing content hub + the forms for the wording of the home page and footer.
 * What is editable is described in config/admin_content.php (groups).
 */
class ContentGroupController extends Controller
{
    protected $editor;

    public function __construct(GroupEditor $editor)
    {
        $this->editor = $editor;
    }

    public function index()
    {
        $hub = [];
        foreach (config('admin_content.hub') as $title => $keys) {
            foreach ($keys as $key) {
                $hub[$title][] = $this->editor->find($key);
            }
        }

        return view('admin.content.index', [
            'hub'    => $hub,
            'tables' => config('admin_content.tables'),
        ]);
    }

    public function edit($group)
    {
        $def = $this->editor->find($group) ?: abort(404);

        return view('admin.content.group', ['group' => $def, 'editor' => $this->editor]);
    }

    public function update(Request $request, $group)
    {
        $def = $this->editor->find($group) ?: abort(404);

        $request->validate($this->editor->rules($def));
        $this->editor->save($def, (array) $request->input('f', []));

        return redirect()->route('admin.landing.edit', $group)->with('success', 'Saved. The website shows the new wording now.');
    }

    public function reset($group)
    {
        $def = $this->editor->find($group) ?: abort(404);

        if ($this->editor->usesSettingsTable($def)) {
            abort(404);
        }

        $this->editor->reset($def);

        return redirect()->route('admin.landing.edit', $group)->with('success', 'Original wording restored.');
    }
}
