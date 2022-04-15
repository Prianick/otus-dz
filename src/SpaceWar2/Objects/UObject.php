<?php

namespace OtusDZ\Src\SpaceWar2\Objects;

interface UObject
{
    public function setProperty($name, object $value);

    public function getProperty($name);
}
