<?php

namespace App\Services;

use App\Repositories\Interfaces\BPJSQueueResponseInterface;

class BPJSQueueResponseService
{
    public function __construct(protected BPJSQueueResponseInterface $bpjsQueueRepository)
    {

    }

    public function create(array $response)
    {
        return $this->bpjsQueueRepository->updateOrCreate([
            'bpjs_number' => $response['bpjs_number'],
            'nik' => $response['nik'],
            'rm_number' => $response['rm_number'],
            'response_status' => $response['metadata']['code'],
            'response_message' => $response['metadata']['message'],
            'response_body' => isset($response['response']) ? json_encode($response['response']) :  null,
        ]);
    }

    public function updateTaskId(array $taskIds){}
}
