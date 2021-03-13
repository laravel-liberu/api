<?php

namespace LaravelEnso\Api;

use Illuminate\Support\Collection;

abstract class Resource
{
    abstract public function toArray(): array;

    public function resolve(): array
    {
        $value = fn ($argument) => $argument instanceof self
            ? $argument->resolve()
            : $argument;

        return array_map($value, $this->toArray());
    }

    public static function collection(Collection $collection): array
    {
        $resource = fn ($item) => (new static($item))->resolve();

        return $collection->map($resource)->toArray();
    }
}
