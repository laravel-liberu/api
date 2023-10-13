<?php

namespace LaravelLiberu\Api\Contracts;

interface UsesAuth
{
    public function tokenProvider(): Token;
}
