<?php

namespace OtusDZ\Src\Dz9ServiceCreation;

interface RequestFunctions
{
    public function getAction(): string;

    public function getJwtToken(): string;

    public function getUserId(): int;
}
