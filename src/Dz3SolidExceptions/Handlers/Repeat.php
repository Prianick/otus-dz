<?php

namespace OtusDZ\Src\Dz3SolidExceptions\Handlers;

use OtusDZ\Src\Dz3SolidExceptions\Commands\Command;
use OtusDZ\Src\Dz3SolidExceptions\Commands\RepeatedCommand;
use OtusDZ\Src\Dz3SolidExceptions\IoC;
use OtusDZ\Src\Dz3SolidExceptions\QueueFunction;
use Throwable;

class Repeat implements Handler
{
    public function handle(Command $command, Throwable $throwable)
    {
        $commandQueue = IoC::getInstance()->get(QueueFunction::class);
        $command = new RepeatedCommand($command);
        $commandQueue->push($command);
    }
}
