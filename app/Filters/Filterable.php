<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;

/**
 * @method static Builder filter(Filters $filters)
 */
trait Filterable
{
    /**
     * @param Builder $query
     * @param Filters $filters
     */
    public function scopeFilter(Builder $query, Filters $filters): void
    {
        $filters->apply($query);
    }
}
