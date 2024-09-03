<?php

namespace App\Repositories\Interfaces;

interface BPJSQueueResponseInterface
{
    public function updateOrCreate(array $response);
}
