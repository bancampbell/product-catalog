<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ProductFilter
{
    protected Builder $query;
    protected Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply(Builder $query): Builder
    {
        $this->query = $query;

        $this->applyCategoryFilter();

        return $this->query;
    }

    protected function applyCategoryFilter(): void
    {
        if ($this->request->has('category_id') && $this->request->category_id) {
            $this->query->where('category_id', $this->request->category_id);
        }
    }
}
