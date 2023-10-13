<?php

namespace LaravelLiberu\Api\Contracts;

interface Endpoint
{
    public function method(): string;

    public function url(): string;

    public function body(): string|array;
}
