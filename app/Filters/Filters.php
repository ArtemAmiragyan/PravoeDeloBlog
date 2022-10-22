<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

abstract class Filters
{
    /**
     * @var array<string>
     */
    protected array $filters = [];

    /**
     * @var Request
     */
    protected Request $request;

    /**
     * @var Builder
     */
    protected Builder $builder;

    /**
     * Filters constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @param Builder $builder
     * @return Builder
     */
    public function apply(Builder $builder): Builder
    {
        $this->builder = $builder;

        foreach ($this->getFilters() as $filter => $value) {
            $filter = $this->camelizeMethodName($filter);

            if (method_exists($this, $filter)) {
                if ($value !== '' && ! is_null($value)) {
                    $this->$filter($value);
                }
            }
        }

        return $this->builder;
    }

    /**
     * @return array<string, ?string>
     */
    public function getFilters(): array
    {
        return $this->request->only($this->filters);
    }

    /**
     * @param string $method
     * @return string
     */
    private function camelizeMethodName(string $method): string
    {
        return str_replace('_', '', ucwords($method, '_'));
    }
}
