<?php

namespace OtusDZ\Src\Dz12ChainOfResponsibility;

class UObject implements \OtusDZ\Src\Dz2MoveAndRotate\Objects\UObject
{
    protected array $props = [];

    public function setProperty($name, $value)
    {
        $this->props[$name] = $value;
    }

    public function getProperty($name)
    {
        return $this->props[$name];
    }
}
