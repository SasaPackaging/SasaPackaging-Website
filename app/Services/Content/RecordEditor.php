<?php

namespace App\Services\Content;

use App\Support\HtmlCleaner;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

/**
 * Edits rows of the website's existing tables described in config/admin_content.php > tables.
 * Only columns that really exist in the table are shown or saved, so a different table layout never crashes.
 */
class RecordEditor
{
    public function find($key)
    {
        // $key comes from the URL: only a plain name is accepted ("banners", never "banners.table")
        if (!is_string($key) || !preg_match('/^[A-Za-z0-9_\-]+$/', $key)) {
            return null;
        }

        $def = config('admin_content.tables.' . $key);
        if (!is_array($def) || !Schema::hasTable($def['table'])) {
            return null;
        }

        $existing = Schema::getColumnListing($def['table']);

        $def['key']    = $key;
        $def['fields'] = array_values(array_filter($def['fields'], function ($f) use ($existing) {
            return in_array($f['name'], $existing, true);
        }));
        $def['columns'] = $existing;

        return $def;
    }

    public function paginate(array $def)
    {
        $q = DB::table($def['table']);

        if (in_array($def['order'], $def['columns'], true)) {
            $q->orderBy($def['order']);
        }

        return $q->orderBy('id')->paginate(config('admin.per_page', 20));
    }

    public function row(array $def, $id)
    {
        return DB::table($def['table'])->where('id', $id)->first();
    }

    public function rules(array $def)
    {
        $rules = [];

        foreach ($def['fields'] as $f) {
            $type = $f['type'];
            $base = !empty($f['required']) ? ['required'] : ['nullable'];

            if ($type === 'status') {
                $rules[$f['name']] = ['required', Rule::in(['Yes', 'No'])];
            } elseif ($type === 'select') {
                $rules[$f['name']] = array_merge($base, [Rule::in(array_keys($f['options']))]);
            } elseif ($type === 'number') {
                $rules[$f['name']] = array_merge($base, ['integer', 'min:0']);
            } else {
                $max = $type === 'rich' ? 200000 : ($type === 'textarea' ? 5000 : 255);
                $rules[$f['name']] = array_merge($base, ['string', 'max:' . $max]);
            }
        }

        return $rules;
    }

    /** Clean values ready for the table. */
    public function values(array $def, array $input)
    {
        $values = [];

        foreach ($def['fields'] as $f) {
            $v = isset($input[$f['name']]) ? $input[$f['name']] : null;

            if ($f['type'] === 'number') {
                $v = ($v === null || $v === '') ? 0 : (int) $v;
            } elseif ($f['type'] === 'rich') {
                $v = HtmlCleaner::clean($v, true);
            } else {
                $v = trim((string) $v);
            }

            $values[$f['name']] = $v;
        }

        if (in_array('updated_at', $def['columns'], true)) {
            $values['updated_at'] = now();
        }

        return $values;
    }

    public function delete(array $def, $id)
    {
        DB::table($def['table'])->where('id', $id)->delete();
    }

    public function update(array $def, $id, array $values)
    {
        DB::table($def['table'])->where('id', $id)->update($values);
    }

    public function create(array $def, array $values)
    {
        if (in_array('created_at', $def['columns'], true)) {
            $values['created_at'] = now();
        }

        return DB::table($def['table'])->insertGetId($values);
    }
}
