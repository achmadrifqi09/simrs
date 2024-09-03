<?php

namespace App\Repositories\Implements;

use App\Models\BPJSQueueResponse;
use App\Repositories\Interfaces\BPJSQueueResponseInterface;

class BPJSQueueResponseRepository implements BPJSQueueResponseInterface
{

    public function updateOrCreate(array $response)
    {
       return BPJSQueueResponse::updateOrCreate($response);
    }
}
