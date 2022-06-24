<?php

namespace OtusDZ\Src\Dz9ServiceCreation;

class JWT implements JWTFunctions
{
    /**
     * @param array $payload
     * @param string $secret
     * @param array|null $headers
     * @return string
     */
    public function getHash(array $payload, string $secret, ?array $headers = []): string
    {
        $headers = array_merge(['alg' => "HS256", "typ" => "JWT"], $headers);
        $unsignedToken = $this->base64urlEncode(json_encode($headers)) . '.' . $this->base64urlEncode(
                json_encode($payload)
            );
        $signature = hash_hmac('sha256', $unsignedToken, $secret);

        return $unsignedToken . '.' . $signature;
    }

    /**
     * @param $data
     * @return string
     */
    public function base64urlEncode($data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    /**
     * @param $data
     * @return string
     */
    public function base64urlDecode($data): string
    {
        return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
    }

    /**
     * @param string $login
     * @param string $password
     * @return string
     */
    public function generateSecret(string $login, string $password): string
    {
        return md5($login . $password . time());
    }

    /**
     * @param string $jwtToken
     * @param string $secret
     * @return bool
     */
    public function checkToken(string $jwtToken, string $secret): bool
    {
        $parts = explode('.', $jwtToken);

        $signature = hash_hmac('sha256', $parts[0] . '.' . $parts[1], $secret);

        return $signature === $parts[2];
    }
}
