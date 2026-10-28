<?php

namespace App\Services\Content;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Reads, validates and saves one "group" of config/admin_content.php (a form of editable wording).
 * Form field names are numeric ("f[3]") so dotted config keys never confuse PHP or the validator.
 */
class GroupEditor
{
    /** @return array|null */
    public function find($key)
    {
        // $key comes from the URL: only a plain name is accepted
        if (!is_string($key) || !preg_match('/^[A-Za-z0-9_\-]+$/', $key)) {
            return null;
        }

        $group = config('admin_content.groups.' . $key);

        return is_array($group) ? $group + ['key' => $key] : null;
    }

    public function usesSettingsTable(array $group)
    {
        return ($group['store'] ?? 'content') === 'settings';
    }

    /** What the form should show now. */
    public function value(array $group, array $field)
    {
        if ($this->usesSettingsTable($group)) {
            $all = $this->settings();
            return isset($all[$field['key']]) ? (string) $all[$field['key']] : '';
        }

        $value = config($field['key']);

        if (($field['type'] ?? 'text') === 'list') {
            return $this->listRows($field, (array) $value);
        }
        if (!empty($field['raw'])) {
            $value = html_entity_decode((string) $value, ENT_QUOTES, 'UTF-8');
        }

        return $value === null ? '' : $value;
    }

    public function rules(array $group)
    {
        $rules = [];

        foreach ($group['fields'] as $i => $field) {
            $type = $field['type'] ?? 'text';

            if ($type === 'list') {
                $rules["f.$i"] = 'nullable|array|max:60';
                foreach ($field['columns'] as $col) {
                    $rules["f.$i.*.{$col['name']}"] = 'nullable|string|max:' . (($col['type'] ?? 'text') === 'textarea' ? 2000 : 300);
                }
            } elseif ($type === 'number') {
                $rules["f.$i"] = 'nullable|numeric|min:0' . (isset($field['max']) ? '|max:' . $field['max'] : '');
            } else {
                $rules["f.$i"] = 'nullable|string|max:' . ($type === 'textarea' ? 2000 : 500) . (isset($field['rule']) ? '|' . $field['rule'] : '');
            }
        }

        return $rules;
    }

    /** Save the submitted form. */
    public function save(array $group, array $input)
    {
        foreach ($group['fields'] as $i => $field) {
            $raw  = isset($input[$i]) ? $input[$i] : null;
            $type = $field['type'] ?? 'text';

            if ($type === 'list') {
                $value = $this->cleanRows($field, (array) $raw);
            } elseif ($type === 'number') {
                $value = ($raw === null || $raw === '') ? null : (strpos((string) $raw, '.') !== false ? (float) $raw : (int) $raw);
            } else {
                $value = trim((string) $raw);
                if (!empty($field['raw'])) {
                    $value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8', false);
                }
            }

            if ($this->usesSettingsTable($group)) {
                DB::table('tbl_settings')->updateOrInsert(['key_name' => $field['key']], ['key_value' => (string) $value]);
            } else {
                ContentOverrides::put($field['key'], $value);
            }
        }
    }

    /** Back to the file defaults. */
    public function reset(array $group)
    {
        ContentOverrides::forget(array_column($group['fields'], 'key'));
    }

    // ---- lists -----------------------------------------------------------------

    protected function listRows(array $field, array $items)
    {
        $rows = [];
        foreach ($items as $item) {
            $rows[] = !empty($field['scalar']) ? ['value' => (string) $item] : (array) $item;
        }

        return $rows;
    }

    protected function cleanRows(array $field, array $rows)
    {
        $out = [];

        foreach ($rows as $row) {
            $row = array_map(function ($v) {
                return trim((string) $v);
            }, (array) $row);

            if (implode('', $row) === '') {
                continue; // empty row
            }

            if (!empty($field['scalar'])) {
                $out[] = $row['value'];
                continue;
            }

            $clean = [];
            foreach ($field['columns'] as $col) {
                $clean[$col['name']] = isset($row[$col['name']]) ? $row[$col['name']] : '';
            }
            $out[] = $clean;
        }

        return $out;
    }

    protected function settings()
    {
        static $all;

        if ($all === null) {
            $all = Schema::hasTable('tbl_settings') ? DB::table('tbl_settings')->pluck('key_value', 'key_name')->all() : [];
        }

        return $all;
    }
}
