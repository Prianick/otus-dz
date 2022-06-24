<?php

namespace OtusDZ\Src\Dz9ServiceCreation;

interface JWTFunctions
{
    /**
     * @param array $payload
     * @param string $secret
     * @param array|null $headers
     * @return string
     */
    public function getHash(array $payload, string $secret, ?array $headers = null): string;

    /**
     * @param string $login
     * @param string $password
     * @return string
     */
    public function generateSecret(string $login, string $password): string;

    /**
     * @param string $jwtToken
     * @param string $secret
     * @return bool
     */
    public function checkToken(string $jwtToken, string $secret): bool;
}
