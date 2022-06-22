<?php

namespace Dz8Messaging;

use OtusDZ\Src\Dz2MoveAndRotate\Moves\Movable;
use OtusDZ\Src\Dz4MacroCommand\Commands\MoveCommand;
use OtusDZ\Src\Dz5IoC\IoC;
use OtusDZ\Src\Dz5IoC\IoCRegister;
use OtusDZ\Src\Dz8Messaging\AccessMiddleware;
use OtusDZ\Src\Dz8Messaging\InterpretCommand;
use OtusDZ\Src\Dz8Messaging\Message;
use OtusDZ\Src\Dz8Messaging\ObjectsPool;
use OtusDZ\Src\Dz8Messaging\QueueManager;
use OtusDZ\Src\Dz8Messaging\RulesManager;
use OtusDZ\Src\Dz8Messaging\StandardRulesManager;
use PHPUnit\Framework\TestCase;

class InterpretCommandTest extends TestCase
{
    public function setUp(): void
    {
        IoC::resolve(IoC::IOC_REGISTER, IoC::IOC_REGISTER, static function () {
            return new IoCRegister();
        })->execute();
        IoC::resolve(IoC::IOC_REGISTER, RulesManager::class, function () {
            return new StandardRulesManager();
        })->execute();

        parent::setUp();
    }

    public function testExecute()
    {
        $authToken = 'authToken';
        $objectId = 1234;
        $operationAlias = 'Tank.move';
        $playerType = 'demoPlayer';

        $queueMock = $this->createMock(QueueManager::class);
        $queueMock->method('pushCommand')->with(
            $this->callback(function ($command) {
                $this->assertEquals(MoveCommand::class, get_class($command));
                return true;
            })
        );
        IoC::resolve(IoC::IOC_REGISTER, AccessMiddleware::class, function () use ($playerType) {
            $mock = $this->createMock(AccessMiddleware::class);
            $mock->method('getPlayerType')
                ->willReturn($playerType);

            return $mock;
        })->execute();

        IoC::resolve(IoC::IOC_REGISTER, QueueManager::class, function () use ($queueMock) {
            return $queueMock;
        })->execute();

        IoC::resolve(IoC::SCOPE_CURRENT, $playerType);
        if (empty(IoC::$scopes[$playerType])) {
            IoC::resolve(IoC::IOC_REGISTER, IoC::IOC_REGISTER, static function () {
                return new IoCRegister();
            })->execute();
        }
        IoC::resolve(IoC::IOC_REGISTER, ObjectsPool::class, function () {
            return $this->createMock(ObjectsPool::class);
        })->execute();
        IoC::resolve(IoC::SCOPE_CURRENT, IoC::DEFAULT_SCOPE);
        $message = $this->createMock(Message::class);
        $message->method('getAuthToken')->willReturn($authToken);
        $message->method('getObjetId')->willReturn($objectId);
        $message->method('getOperationAlias')->willReturn($operationAlias);
        $command = new InterpretCommand($message);
        $command->execute();
    }
}
