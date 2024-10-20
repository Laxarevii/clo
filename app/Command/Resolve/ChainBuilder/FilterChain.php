<?php

namespace App\Command\Resolve\ChainBuilder;

class FilterChain
{
    protected array $filters;

    public function __construct(array $filters)
    {
        $this->filters = $filters;
    }

    public function process(Request $request): bool
    {
        foreach ($this->filters as $filter) {
            if (!$filter->apply($request)) {
                return false; // Фильтр не прошел
            }
        }
        return true; // Все фильтры прошли
    }
}
