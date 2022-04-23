<?php

namespace OtusDZ\Src\Dz3SolidExceptions\Handlers;

use Exception;
use OtusDZ\Src\Dz3SolidExceptions\Commands\Command;
use OtusDZ\Src\Dz3SolidExceptions\Commands\RepeatedCommand;
use OtusDZ\Src\Dz3SolidExceptions\IoC;
use OtusDZ\Src\Dz3SolidExceptions\QueueFunction;
use Throwable;

class SecondStrategyList
{
    public static function all(): array
    {
        return [
            md5(RepeatedCommand::class . Exception::class) =>
                static function (RepeatedCommand $command, Throwable $throwable): void {
                    if ($command->getCount() < 3) {
                        $commandQueue = IoC::getInstance()->get(QueueFunction::class);
                        $commandQueue->push($command);
                    } else {
                        (new Log())->handle($command, $throwable);
                    }
                },

            Exception::class =>
                static function (Command $command, Throwable $throwable): void {
                    (new Repeat())->handle($command, $throwable);
                },
        ];
    }
}
