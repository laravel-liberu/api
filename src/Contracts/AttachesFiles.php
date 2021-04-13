<?php

namespace LaravelEnso\Api\Contracts;

use Illuminate\Http\Client\PendingRequest;

interface AttachesFiles
{
    public function attach(PendingRequest $http): PendingRequest;
}
