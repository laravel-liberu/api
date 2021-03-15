<?php

namespace LaravelEnso\Api\Contracts;

interface Retry
{
    public function delay(): int;

    public function tries(): int;
}
