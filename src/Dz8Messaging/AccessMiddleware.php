<?php

namespace OtusDZ\Src\Dz8Messaging;

interface AccessMiddleware
{
    public function getPlayerType(string $authToken): string;
}
