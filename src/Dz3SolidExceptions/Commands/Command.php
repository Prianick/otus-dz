<?php

namespace OtusDZ\Src\Dz3SolidExceptions\Commands;

interface Command
{
    public function setArgs(array $args);

    public function execute();
}
