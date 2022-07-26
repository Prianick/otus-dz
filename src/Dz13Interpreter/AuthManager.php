<?php

namespace OtusDZ\Src\Dz13Interpreter;

interface AuthManager
{
    public function checkAccessToObject(int $objectId): bool;
}
