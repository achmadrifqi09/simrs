<?php

namespace App\Utils;

use Exception;
use Illuminate\Support\Facades\Http;

class ClientRequest
{
    /**
     * @throws Exception
     */
    private static function requestConfigs(string $resource, string $endpoint, array $headers = []) : array
    {
        if(!in_array($resource, ['vclaim', 'queue'])) throw new Exception('Invalid resource');

        $url = config('bpjs.api.base_url') . "/" . config("bpjs.api." . $resource . "_service_name") . "/" . $endpoint;

        $headers = empty($headers) ?
            HeaderGenerator::create(
                config("bpjs.api.const_id"),
                config("bpjs.api.secret_key"),
                config("bpjs.api." . $resource ."_user_key")
            ) : array_merge( HeaderGenerator::create(
                config("bpjs.api.const_id"),
                config("bpjs.api.secret_key"),
                config("bpjs.api." . $resource ."_user_key")
            ), $headers);

        return [
            'headers' => $headers,
            'uri' => $url,
        ];
    }

    private static function checkResponse($res, array $configs)
    {
        $res = json_decode($res, true);
        $res = array_change_key_case($res, CASE_LOWER);

        if (in_array($res['metadata']['code'], [1, 200]) && array_key_exists('response', (array) $res)) {
            if ($res['response'] != null) {
                $keyDecrypt = $configs['headers']['X-cons-id'] .  config("bpjs.api.secret_key") . $configs['headers']['X-timestamp'];
                $response = EncryptionHelper::decryptAndCompress($keyDecrypt, $res['response']);
                $res['response'] = json_decode($response, true);
            }

        }

        return $res;
    }

    /**
     * @throws Exception
     */
    public static function get(string $endpoint, string $resource, $headers = [])
    {

        $configs = self::requestConfigs($resource, $endpoint, $headers);
        try{
            $res = Http::withHeaders($configs['headers'])->get($configs['uri']);
            $res = self::checkResponse($res, $configs);
        }catch (Exception $e){
            $res['metadata']['code'] = "400";

            $res['metadata']['message'] = str_contains($e->getMessage(), 'time out') || str_contains($e->getMessage(), 'timed out ')
                ? "Koneksi ke BPJS terputus, sistem BPJS tidak menanggapi permintaan" :
                'Terjadi kesalahan pada server BPJS, ' . $e->getMessage();
        }
        return $res;
    }

    /**
     * @throws Exception
     */
    public static function post(string $endpoint, array $data, string $resource, $headers = [])
    {
        $configs = self::requestConfigs($resource, $endpoint, $headers);
        try {
            $res = Http::withHeaders($configs['headers'])->post($configs['uri'], $data);
            $res = self::checkResponse($res, $configs);
        }catch (Exception $e){
            $res['metadata']['code'] = "400";

            $res['metadata']['message'] = str_contains($e->getMessage(), 'time out') || str_contains($e->getMessage(), 'timed out ')
                ? "Koneksi ke BPJS terputus, sistem BPJS tidak menanggapi permintaan" :
                'Terjadi kesalahan pada server BPJS, ' . $e->getMessage();
        }
        return $res;
    }

}
