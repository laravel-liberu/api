<?php

namespace LaravelEnso\Api\Contracts;

interface Token
{
    public function type(): string;

    public function auth(): self;

    public function current(): string;
}
