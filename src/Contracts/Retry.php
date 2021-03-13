<?php

namespace LaravelEnso\Api\Contracts;

interface Retry
{
    public function tries(): int;
}
