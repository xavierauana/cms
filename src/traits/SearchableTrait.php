<?php
/**
 * Author: Xavier Au
 * Date: 2019-01-18
 * Time: 10:30
 */

namespace Anacreation\Cms\traits;


use Illuminate\Database\Eloquent\Builder;

trait SearchableTrait
{
    public function scopeSearchable(Builder $query, string $keyword = null
    ): Builder {
        if (isset($this->searchableColumns) and
            count($this->searchableColumns) > 0 and
            $keyword) {
            foreach ($this->searchableColumns as $index => $column) {
                if ($index === 0) {
                    $query->where($column, 'like', "%{$keyword}%");
                } else {
                    $query->orWhere($column, 'like', "%{$keyword}%");
                }
            }
        }

        return $query;
    }
}