<?php

namespace OtusDZ\Src\Dz3SolidExceptions\Handlers;

use OtusDZ\Src\Dz3SolidExceptions\Commands\Command;
use Throwable;

interface Handler
{
    public function handle(Command $command, Throwable $throwable);
}
