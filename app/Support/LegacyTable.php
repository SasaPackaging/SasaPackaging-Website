<?php

namespace App\Support;

use Illuminate\Support\Facades\DB;

/**
 * Helpers for the website's old tables (they have columns this admin does not know about).
 */
class LegacyTable
{
    /**
     * Extra NOT NULL columns without a default would make an insert fail.
     * Returns a harmless empty value for each of them (MySQL only; other drivers return nothing).
     */
    public static function requiredDefaults($table, array $given)
    {
        $extra = [];

        try {
            if (DB::connection()->getDriverName() !== 'mysql') {
                return $extra;
            }
            foreach (DB::select('SHOW COLUMNS FROM `' . str_replace('`', '', $table) . '`') as $c) {
                $name = $c->Field;
                if ($c->Null !== 'NO' || $c->Default !== null || stripos($c->Extra, 'auto_increment') !== false
                    || in_array($name, $given, true) || in_array($name, ['id', 'created_at', 'updated_at'], true)) {
                    continue;
                }
                if (preg_match('/^enum\((.*)\)/i', $c->Type, $m)) {
                    preg_match_all("/'([^']*)'/", $m[1], $opts);
                    $extra[$name] = in_array('No', $opts[1], true) ? 'No' : (isset($opts[1][0]) ? $opts[1][0] : '');
                } elseif (preg_match('/int|decimal|float|double|bit/i', $c->Type)) {
                    $extra[$name] = 0;
                } elseif (preg_match('/^(date|datetime|timestamp)/i', $c->Type)) {
                    $extra[$name] = now();
                } else {
                    $extra[$name] = '';
                }
            }
        } catch (\Throwable $e) {
            // not critical: the normal insert is tried anyway
        }

        return $extra;
    }
}
