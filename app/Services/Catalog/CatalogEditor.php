<?php

namespace App\Services\Catalog;

use App\Services\Media\ImageStore;
use App\Support\HtmlCleaner;
use App\Support\LegacyTable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

/**
 * List / add / edit / delete for the rows described in config/catalog.php (services, products),
 * including their pictures. Only columns that exist in the table are used.
 */
class CatalogEditor
{
    public function find($type)
    {
        $def = config('catalog.' . $type);
        if (!is_array($def) || !isset($def['table']) || !Schema::hasTable($def['table'])) {
            return null;
        }

        $cols = Schema::getColumnListing($def['table']);

        $fields = [];
        foreach ($def['fields'] as $f) {
            if ($f['type'] === 'image') {
                $f['saves'] = array_values(array_intersect($f['saves'], $cols));
                if (!$f['saves']) {
                    continue;
                }
            } elseif (!in_array($f['name'], $cols, true)) {
                continue;
            }
            if ($f['type'] === 'select' && $f['options'] === 'categories') {
                $f['options'] = Schema::hasTable('tbl_category')
                    ? DB::table('tbl_category')->orderBy('name')->pluck('name', 'id')->all() : [];
            }
            $fields[] = $f;
        }

        $def['key']     = $type;
        $def['columns'] = $cols;
        $def['fields']  = $fields;

        return $def;
    }

    protected function query(array $def)
    {
        $q = DB::table($def['table']);
        foreach ($def['where'] as $col => $val) {
            $q->where($col, $val);
        }

        return $q;
    }

    public function paginate(array $def, $search = '')
    {
        $q = $this->query($def);

        if ($search !== '' && in_array($def['title'], $def['columns'], true)) {
            $q->where($def['title'], 'like', \App\Support\Sanitize::like($search));
        }
        if (in_array($def['order'], $def['columns'], true)) {
            $q->orderBy($def['order']);
        }

        return $q->orderBy('id')->paginate(config('admin.per_page', 20))->appends(['q' => $search]);
    }

    public function row(array $def, $id)
    {
        return $this->query($def)->where('id', $id)->first();
    }

    public function rules(array $def)
    {
        $images = config('catalog.images');
        $rules  = [];

        foreach ($def['fields'] as $f) {
            $base = !empty($f['required']) ? ['required'] : ['nullable'];

            switch ($f['type']) {
                case 'image':
                    $rules[$f['name']] = ['nullable', 'file', 'mimes:' . implode(',', $images['extensions']), 'max:' . $images['max_kb']];
                    break;
                case 'status':
                    $rules[$f['name']] = ['required', Rule::in(['Yes', 'No'])];
                    break;
                case 'select':
                    $rules[$f['name']] = array_merge($base, [Rule::in(array_keys($f['options']))]);
                    break;
                case 'number':
                    $rules[$f['name']] = array_merge($base, ['integer', 'min:0', 'max:100000']);
                    break;
                default:
                    $max = $f['type'] === 'rich' ? 200000 : ($f['type'] === 'textarea' ? 2000 : 190);
                    $rules[$f['name']] = array_merge($base, ['string', 'max:' . $max]);
            }
        }

        return $rules;
    }

    /** field names as the admin reads them in error messages */
    public function attributes(array $def)
    {
        $a = [];
        foreach ($def['fields'] as $f) {
            $a[$f['name']] = strtolower($f['label']);
        }

        return $a;
    }

    /**
     * Saves the row (new when $row is null). Returns the row id.
     * @param array $input  request values
     * @param array $files  field name => UploadedFile
     */
    public function save(array $def, $row, array $input, array $files = [])
    {
        $values = [];

        foreach ($def['fields'] as $f) {
            if ($f['type'] === 'image') {
                continue;
            }
            $v = isset($input[$f['name']]) ? $input[$f['name']] : null;

            if ($f['type'] === 'number') {
                $v = ($v === null || $v === '') ? ($f['name'] === $def['order'] ? null : 0) : (int) $v;
            } elseif ($f['type'] === 'rich') {
                $v = HtmlCleaner::clean($v, true);
            } else {
                $v = trim((string) $v);
            }
            $values[$f['name']] = $v;
        }

        if (in_array($def['order'], $def['columns'], true) && (!isset($values[$def['order']]) || $values[$def['order']] === null)) {
            $values[$def['order']] = $row && isset($row->{$def['order']}) ? $row->{$def['order']} : $this->query($def)->max($def['order']) + 1;
        }

        // pictures: new files are stored first, old ones removed only after the row is saved
        $stored = [];   // [dir, file]
        $oldFiles = []; // [dir, file]

        try {
            foreach ($def['fields'] as $f) {
                if ($f['type'] !== 'image' || empty($files[$f['name']])) {
                    continue;
                }
                $file = ImageStore::store($files[$f['name']], $f['dir'], $this->title($def, $values), config('catalog.images.extensions'));
                $stored[] = [$f['dir'], $file];

                foreach ($f['saves'] as $col) {
                    if ($row && !empty($row->{$col})) {
                        $oldFiles[] = [$f['dir'], $row->{$col}, $col];
                    }
                    $values[$col] = $file;
                }
            }

            if ($row) {
                if (in_array('updated_at', $def['columns'], true)) {
                    $values['updated_at'] = now();
                }
                DB::table($def['table'])->where('id', $row->id)->update($values);
                $id = $row->id;
            } else {
                $values = $this->forNewRow($def, $values);
                $id = DB::table($def['table'])->insertGetId($values);
            }
        } catch (\Throwable $e) {
            foreach ($stored as $s) {
                ImageStore::delete($s[0], $s[1]);
            }
            throw $e;
        }

        foreach ($oldFiles as $o) {
            $this->removeFileIfFree($def, $o[0], $o[1], $id);
        }

        return $id;
    }

    public function delete(array $def, $row)
    {
        $this->query($def)->where('id', $row->id)->delete();

        foreach ($def['fields'] as $f) {
            if ($f['type'] !== 'image') {
                continue;
            }
            foreach ($f['saves'] as $col) {
                $this->removeFileIfFree($def, $f['dir'], isset($row->{$col}) ? $row->{$col} : '', $row->id);
            }
        }
    }

    /** address, fixed values, dates and harmless values for columns this admin does not know */
    protected function forNewRow(array $def, array $values)
    {
        $cols = $def['columns'];

        if (in_array('slug', $cols, true)) {
            $values['slug'] = $this->uniqueSlug($this->title($def, $values));
        }
        $values = array_merge($values, array_intersect_key($def['create'], array_flip($cols)));
        $values = $values + LegacyTable::requiredDefaults($def['table'], array_keys($values));

        foreach (['created_at', 'updated_at'] as $c) {
            if (in_array($c, $cols, true)) {
                $values[$c] = now();
            }
        }

        return $values;
    }

    protected function title(array $def, array $values)
    {
        return isset($values[$def['title']]) && $values[$def['title']] !== '' ? $values[$def['title']] : (isset($values['name']) ? $values['name'] : 'item');
    }

    protected function uniqueSlug($title)
    {
        $base = Str::slug($title) ?: 'item';
        $slug = $base;
        $n    = 2;

        while ($this->slugTaken($slug)) {
            $slug = $base . '-' . $n++;
        }

        return $slug;
    }

    protected function slugTaken($slug)
    {
        foreach (config('catalog.slug_tables') as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'slug') && DB::table($table)->where('slug', $slug)->exists()) {
                return true;
            }
        }

        return false;
    }

    /** A picture file is deleted only when no other row of the table still uses it. */
    protected function removeFileIfFree(array $def, $dir, $file, $exceptId)
    {
        if (!$file) {
            return;
        }

        foreach ($def['fields'] as $f) {
            if ($f['type'] !== 'image') {
                continue;
            }
            foreach ($f['saves'] as $col) {
                if (DB::table($def['table'])->where($col, $file)->where('id', '!=', $exceptId)->exists()) {
                    return;
                }
                // still used by another picture column of this same row?
                if (DB::table($def['table'])->where($col, $file)->where('id', $exceptId)->exists()) {
                    return;
                }
            }
        }

        ImageStore::delete($dir, $file);
    }
}
