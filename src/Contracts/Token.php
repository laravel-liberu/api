<?php

namespace LaravelEnso\Api\Contracts;

interface Token
{
    public function auth(): void;

    public function current(): string;
}
