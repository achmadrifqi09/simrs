<?php

return [
    'api' => [
        'base_url' => env('BPJS_API_MODE') === 'prod' ? 'https://apijkn.bpjs-kesehatan.go.id' : 'https://apijkn-dev.bpjs-kesehatan.go.id',
        'const_id' => env('BPJS_API_MODE') === 'prod' ? '24440' : '17497',
        'secret_key' => env('BPJS_API_MODE') === 'prod' ? '0bOD41EEBE' : '7sE8D580DC',
        'queue_user_key' => env('BPJS_API_MODE') === 'prod' ? '75404f79a0b2dc0e5158ee9b9bcdc264' : '3863bd18b78e867d287bea76d67cd942',
        'vclaim_user_key' => env('BPJS_API_MODE') === 'prod' ? '0c85086069cfab07b2774e1d1cc26982' : '83a9cd09ab222d92ea4616ed795ff5c0',
        'queue_service_name' => env('BPJS_API_MODE') === 'prod' ? 'antreanrs' : 'antreanrs_dev',
        'vclaim_service_name' => env('BPJS_API_MODE') === 'prod' ? 'vclaim-rest' : 'vclaim-rest-dev',
    ]
];
