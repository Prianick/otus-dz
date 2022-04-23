<?php

namespace OtusDZ\Src\Dz2MoveAndRotate\Objects;

interface UObject
{
    public function setProperty($name, object $value);

    public function getProperty($name);
}
