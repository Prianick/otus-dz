<?php

namespace OtusDZ\Src\Dz3SolidExceptions;

use Closure;
use OtusDZ\Src\Dz2MoveAndRotate\Objects\UObject;
use ReflectionMethod;

class IoC
{
    public array $entities;

    private static IoC $instance;

    public static function getInstance(): self
    {
        if (empty(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

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
