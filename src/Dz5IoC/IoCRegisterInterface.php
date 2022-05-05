<?php

namespace OtusDZ\Src\Dz5IoC;

use Closure;

interface IoCRegisterInterface
{
    public function get($entityName, ?array $args = []);

    public function set($entityName, Closure $func);
}
