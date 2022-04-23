<?php

namespace OtusDZ\Src\Dz3SolidExceptions\Commands;

use OtusDZ\Src\Dz3SolidExceptions\IoC;
use OtusDZ\Src\Dz3SolidExceptions\Logger;
use Throwable;

class LogCommand implements Command
{
    public Throwable $throwable;
    public Command $command;

    public function __construct(Command $command, Throwable $exception)
    {
        $this->command = $command;
        $this->throwable = $exception;
    }

    public function execute()
    {
        /** @var Logger $logger */
        $logger = IoC::getInstance()->get('Logger');
        $logger->printInfo(
            $this->throwable->getMessage(),
            $this->throwable->getCode(),
            get_class($this->command)
        );
    }
}
