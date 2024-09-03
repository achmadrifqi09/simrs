<?php

namespace App\Utils;

use LZCompressor\LZString;

class EncryptionHelper
{
    public static function encodedSignature(string $consId, string $secretKey) : array
    {
        date_default_timezone_set('UTC');
        $timestamp = strval(time() - strtotime('1970-01-01 00:00:00'));
        $signature = hash_hmac('sha256', $consId . "&" . $timestamp, $secretKey, true);

        $encodedSignature = base64_encode($signature);
        return [
            'timestamp' => $timestamp,
            'signature' => $encodedSignature,
        ];
    }

    private static function stringDecryption($key, $payload)
    {
        $encrypt_method = 'AES-256-CBC';
        $key_hash = hex2bin(hash('sha256', $key));
        $iv = substr(hex2bin(hash('sha256', $key)), 0, 16);
        return openssl_decrypt(base64_decode($payload), $encrypt_method, $key_hash, OPENSSL_RAW_DATA, $iv);
    }

    public static function decompress($payload)
    {
        return LZString::decompressFromEncodedURIComponent($payload);
    }

    public static function decryptAndCompress(string $key, $payload)
    {
        return self::decompress(self::stringDecryption($key, $payload));
    }

}
