<?php

namespace OtusDZ\Src\Dz3SolidExceptions\Handlers;

use Exception;
use OtusDZ\Src\Dz3SolidExceptions\Commands\Command;
use OtusDZ\Src\Dz3SolidExceptions\Commands\RepeatedCommand;
use Throwable;

class FirstsStrategyList
{
    public static function all(): array
    {
        return [
            Exception::class =>
                static function (Command $command, Throwable $throwable): void {
                    (new Repeat())->handle($command, $throwable);
                },

            md5(RepeatedCommand::class . Exception::class) =>
                static function (Command $command, Throwable $throwable): void {
                    (new Log())->handle($command, $throwable);
                },
        ];
    }
}
