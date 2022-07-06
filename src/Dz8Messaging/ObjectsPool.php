<?php

namespace OtusDZ\Src\Dz8Messaging;

use OtusDZ\Src\Dz2MoveAndRotate\Objects\UObject;

interface ObjectsPool
{
    public function getObject(string $id): UObject;

    public function setObject(string $type, array $params): void;
}
