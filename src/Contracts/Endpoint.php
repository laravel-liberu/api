<?php

namespace LaravelEnso\Api\Contracts;

interface Endpoint
{
    public function method(): string;

    public function path(): string;

    public function body(): array;
}