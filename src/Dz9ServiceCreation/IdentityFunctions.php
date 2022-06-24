<?php

namespace OtusDZ\Src\Dz9ServiceCreation;

interface IdentityFunctions
{
    public static function getInstance(): self;

    public function getAuthUser(): User;

    public function getJwtToken(): string;

    public function setCurrentUser(int $userId): void;
}
