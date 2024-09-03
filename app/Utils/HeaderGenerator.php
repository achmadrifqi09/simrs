<?php

namespace App\Utils;
class HeaderGenerator
{
    public static function create(string $consId, string $secretKey, $userKey) : array
    {
        $encryptionData = EncryptionHelper::encodedSignature($consId, $secretKey);
        return [
            'X-cons-id' => $consId,
            'X-timestamp' => $encryptionData['timestamp'],
            'X-signature' => $encryptionData['signature'],
            'user_key' => $userKey
        ];
    }
}
