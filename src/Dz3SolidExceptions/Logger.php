<?php

namespace OtusDZ\Src\Dz3SolidExceptions;

interface Logger
{
    public function printInfo(string $text, int $code, string $commandName);
}
