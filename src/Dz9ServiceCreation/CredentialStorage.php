<?php

namespace OtusDZ\Src\Dz9ServiceCreation;

interface CredentialStorage
{
    public function checkPass($login, $pass): bool;

    public function getUserId($login): int;

    public function saveJWT(string $login, string $jwt);

    public function getSecret(int $userId): string;

    public function createGame(array $listOfPlayers, $mainPlayerId): int;
}
