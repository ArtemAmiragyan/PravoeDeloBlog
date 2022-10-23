<?php

namespace App\Filters;

class ArticleFilters extends Filters
{
    /**
     * @var array<string>
     */
    protected array $filters = [
        'query',
        'categories',
        'date_from',
        'date_to',
        'user_id',
    ];

    /**
     * @param string|null $q
     */
    protected function query(string $q = null): void
    {
        $this->builder->where('title', 'like', "%{$q}%");
    }

    /**
     * @param array<string>|null $categories
     */
    protected function categories(array $categories = null): void
    {
        $this->builder->whereIn('category', $categories);
    }

    /**
     * @param string|null $dateFrom
     */
    protected function dateFrom(string $dateFrom = null): void
    {
        $this->builder->where('created_at', '>=', $dateFrom);
    }

    /**
     * @param string|null $dateTo
     */
    protected function dateTo(string $dateTo = null): void
    {
        $this->builder->where('created_at', '<=', $dateTo);
    }

    /**
     * @param string|null $userId
     */
    protected function userId(string $userId = null): void
    {
        $this->builder->where('user_id', $userId);
    }
}
