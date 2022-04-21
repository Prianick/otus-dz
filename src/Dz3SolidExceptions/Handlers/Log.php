<?php

namespace OtusDZ\Src\Dz3SolidExceptions\Handlers;

use OtusDZ\Src\Dz3SolidExceptions\Commands\Command;
use OtusDZ\Src\Dz3SolidExceptions\Commands\LogCommand;
use OtusDZ\Src\Dz3SolidExceptions\IoC;
use OtusDZ\Src\Dz3SolidExceptions\QueueFunction;
use Throwable;

class Log implements Handler
{
    public function handle(Command $command, Throwable $throwable)
    {
        /** @var QueueFunction $commandQueue */
        $commandQueue = IoC::getInstance()->get(QueueFunction::class);
        $command = new LogCommand($command, $throwable);
        $commandQueue->push($command);
    }
}
