<?php

namespace LaravelLiberu\Api;

use Illuminate\Support\Collection;
use LaravelLiberu\Api\Exceptions\Filters;

abstract class Filter
{
    public function __construct(private readonly array $filters)
    {
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
