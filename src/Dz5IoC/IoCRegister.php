<?php

namespace OtusDZ\Src\Dz5IoC;

use Closure;

class IoCRegister implements IoCRegisterInterface
{
    public array $entities;

    public function get($entityName, ?array $args = [])
    {
        if (count($args) === 0) {
            return $this->entities[$entityName]();
        } else {
            return call_user_func_array($this->entities[$entityName], $args);
        }
    }

    public function set($entityName, Closure $func)
    {
        $this->entities[$entityName] = $func;
    }
}
