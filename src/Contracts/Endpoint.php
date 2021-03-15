<?php

namespace LaravelEnso\Api\Contracts;

interface Endpoint
{
    public function method(): string;

    public function url(): string;

    public function body(): array;
}
