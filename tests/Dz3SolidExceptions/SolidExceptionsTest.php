<?php

namespace Dz3SolidExceptions;

use Closure;
use Exception;
use OtusDZ\Src\Dz3SolidExceptions\Commands\Command;
use OtusDZ\Src\Dz3SolidExceptions\Commands\LogCommand;
use OtusDZ\Src\Dz3SolidExceptions\Commands\RepeatedCommand;
use OtusDZ\Src\Dz3SolidExceptions\ExceptionHandler;
use OtusDZ\Src\Dz3SolidExceptions\Handlers\FirstsStrategyList;
use OtusDZ\Src\Dz3SolidExceptions\Handlers\Log;
use OtusDZ\Src\Dz3SolidExceptions\Handlers\SecondStrategyList;
use OtusDZ\Src\Dz3SolidExceptions\IoC;
use OtusDZ\Src\Dz3SolidExceptions\QueueFunction;
use PHPUnit\Framework\TestCase;
use Throwable;

class SolidExceptionsTest extends TestCase
{
    private function setFakeQueue(Closure $checkingFunc)
    {
        $queue = $this->createMock(QueueFunction::class);
        $queue->expects($this->any())
            ->method('push')
            ->with(
                $this->callback($checkingFunc)
            );
        IoC::getInstance()->set(QueueFunction::class, function () use ($queue) {
            return $queue;
        });
    }

    public function testLogHandler()
    {
        $cmd = $this->createMock(Command::class);
        $e = $this->createMock(Exception::class);
        $this->setFakeQueue(function ($subject) use ($cmd) {
            return get_class($subject->command) === get_class($cmd);
        });
        $logHandler = new Log();
        $logHandler->handle($cmd, $e);
        $this->assertEquals(1, 1);
    }

    public function testRepeat()
    {
        $cmd = $this->createMock(Command::class);
        $cmd->expects($this->once())->method('execute');
        $repeatCommand = new RepeatedCommand($cmd);
        $repeatCommand->execute();
    }

    public function testFirstStrategyExceptionHandling()
    {
        $cmd = $this->createMock(Command::class);
        $cmd->method('execute')->will($this->throwException(new Exception()));
        $exceptionHandler = new ExceptionHandler();
        $exceptionHandler->addHandlers(FirstsStrategyList::all());
        $i = 0;
        $counter = &$i;
        $this->setFakeQueue(function ($subject) use ($cmd, &$counter) {
            if ($counter === 1) {
                return get_class($subject) === RepeatedCommand::class;
            } else {
                return get_class($subject) === LogCommand::class;
            }
        });
        $repeatedCmd = new RepeatedCommand($cmd);
        $queue = [
            $cmd,
            $repeatedCmd,
        ];
        /** @var Command $command */
        foreach ($queue as $command) {
            try {
                $i++;
                $command->execute();
            } catch (Throwable $exception) {
                $exceptionHandler->handle($command, $exception);
            }
        }
        $this->assertEquals(1, 1);
    }

    public function testSecondStrategyExceptionHandling()
    {
        $cmd = $this->createMock(Command::class);
        $cmd->method('execute')->will($this->throwException(new Exception()));
        $exceptionHandler = new ExceptionHandler();
        $exceptionHandler->addHandlers(SecondStrategyList::all());
        $i = 0;
        $counter = &$i;
        $this->setFakeQueue(function ($subject) use ($cmd, &$counter) {
            if ($counter < 4) {
                return get_class($subject) === RepeatedCommand::class;
            } else {
                return get_class($subject) === LogCommand::class;
            }
        });
        $repeatedCmd = new RepeatedCommand($cmd);
        $queue = [
            $cmd,
            $repeatedCmd,
            $repeatedCmd,
            $repeatedCmd,
        ];
        /** @var Command $command */
        foreach ($queue as $command) {
            try {
                $i++;
                $command->execute();
            } catch (Throwable $exception) {
                $exceptionHandler->handle($command, $exception);
            }
        }
        $this->assertEquals(1, 1);
    }
}
