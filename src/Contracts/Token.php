<?php

namespace LaravelEnso\Api\Contracts;

interface Token
{
    public function auth(): self;

    public function current(): string;
}
