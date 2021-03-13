<?php

namespace LaravelEnso\Api;

use Illuminate\Support\Collection;
use LaravelEnso\Api\Exceptions\Filters;

abstract class Filter
{
    private array $filters;

    public function __construct(array $filters)
    {
        $this->filters = $filters;
    }

    abstract public function allowed(): array;

    public function toArray(): array
    {
        $invalid = Collection::wrap($this->filters)->keys()
            ->diff($this->allowed());

        if ($invalid->isNotEmpty()) {
            throw Filters::invalid($invalid->implode(', '));
        }

        return $this->filters;
    }
}
