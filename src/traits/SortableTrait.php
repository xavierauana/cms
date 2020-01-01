<?php
/**
 * Author: Xavier Au
 * Date: 2019-01-18
 * Time: 10:30
 */

namespace Anacreation\Cms\traits;


use Illuminate\Database\Eloquent\Builder;

trait SortableTrait
{
    public function scopeSortable(Builder $query): Builder {
        $columns = $this->sortableColumns ?? [];
        if ($sortBy = request()->query('sortable') and
            in_array($sortBy, $columns)) {
            $order = request()->query('sortableOrder') == 'asc' ?
                'asc' :
                'desc';

            $query->orderBy($sortBy, $order);
        }

        return $query;

    }
}