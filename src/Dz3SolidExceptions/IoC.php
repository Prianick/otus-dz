<?php

namespace OtusDZ\Src\Dz3SolidExceptions;

use Closure;

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
        return call_user_func($this->entities[$entityName], $args);
    }

    public function set($entityName, Closure $func)
    {
        $this->entities[$entityName] = $func;
    }
}
