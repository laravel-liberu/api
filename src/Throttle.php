<?php

namespace LaravelEnso\Api;

class Throttle
{
    private float $time;

    public function __construct(private readonly int $debounce)
    {
        $this->time = $this->now();
    }

    public function __invoke(): self
    {
        $diff = $this->now() - $this->time;

        if ($diff < $this->debounce) {
            sleep($this->debounce - $diff);
        }

        $this->time = $this->now();

        return $this;
    }

    private function now(): int
    {
        return (int) microtime(true);
    }
}
