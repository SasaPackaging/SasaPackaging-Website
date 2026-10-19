<?php

namespace App\Services\Quote;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Everything the quote form needs for its drop-downs.
 *  - Fixed lists (units, stock, coating ...)  come from config/quote.php
 *  - Products and services                    come from the website's own tables
 */
class QuoteOptionsService
{
    /** Lists that are plain config: units, stocks, coatings, add_ons, colours, packings */
    public function fixed()
    {
        return [
            'units'    => config('quote.units'),
            'stocks'   => config('quote.stocks'),
            'coatings' => config('quote.coatings'),
            'add_ons'  => config('quote.add_ons'),
            'colours'  => config('quote.colours'),
            'packings' => config('quote.packings'),
        ];
    }

    /** [id => name] of products the visitor can pick. Empty when the table is not there yet. */
    public function products()
    {
        return $this->catalog('products');
    }

    /** [id => name] of services the visitor can pick. */
    public function services()
    {
        return $this->catalog('services');
    }

    /** Everything the Blade view needs in one array. */
    public function forForm()
    {
        $file = config('quote.file');

        return $this->fixed() + [
            'products' => $this->products(),
            'services' => $this->services(),
            'file'     => [
                'accept'     => '.' . implode(',.', $file['extensions']),
                'types'      => implode(', ', $file['extensions']),
                'max_mb'     => round($file['max_kb'] / 1024),
            ],
        ];
    }

    /** Name of a catalogue item, or null. Used to keep a copy of the name with the request. */
    public function label($type, $id)
    {
        $list = $this->catalog($type);

        return isset($list[(int) $id]) ? $list[(int) $id] : null;
    }

    protected function catalog($type)
    {
        $c = config('quote.catalog.' . $type);

        if (!$c || !Schema::hasTable($c['table'])
            || !Schema::hasColumn($c['table'], $c['id'])
            || !Schema::hasColumn($c['table'], $c['label'])) {
            return [];
        }

        $query = DB::table($c['table']);

        if (!empty($c['active']) && Schema::hasColumn($c['table'], $c['active']['column'])) {
            $query->where($c['active']['column'], $c['active']['value']);
        }

        if (!empty($c['order']) && Schema::hasColumn($c['table'], $c['order'])) {
            $query->orderBy($c['order']);
        }

        $query->orderBy($c['label']);

        $list = [];
        foreach ($query->get([$c['id'] . ' as id', $c['label'] . ' as label']) as $row) {
            $label = trim(strip_tags((string) $row->label));
            if ($label !== '') {
                $list[(int) $row->id] = $label;
            }
        }

        return $list;
    }
}
