<?php

namespace Dz13Interpreter;

use OtusDZ\Src\Dz13Interpreter\AuthManager;
use OtusDZ\Src\Dz13Interpreter\IGameOrder;
use OtusDZ\Src\Dz12ChainOfResponsibility\UObject;
use OtusDZ\Src\Dz13Interpreter\Interpreter;
use OtusDZ\Src\Dz13Interpreter\InterpretOrderCommand;
use OtusDZ\Src\Dz13Interpreter\StartMove;
use OtusDZ\Src\Dz5IoC\IoC;
use OtusDZ\Src\Dz5IoC\IoCRegister;
use OtusDZ\Src\Dz6AdapterGen\AdapterCreator;
use OtusDZ\Src\Dz8Messaging\QueueManager;
use PHPUnit\Framework\TestCase;

class InterpreterTest extends TestCase
{
    public function testInterpreter()
    {
        $order = [
            'id' => '548',
            'action' => 'StartMove',
            'initialVelocity' => 2,
        ];

        IoC::resolve(
            IoC::IOC_REGISTER,
            IoC::IOC_REGISTER,
            function () {
                return new IoCRegister();
            }
        )->execute();

        IoC::resolve(
            IoC::IOC_REGISTER,
            QueueManager::class,
            function () use ($order) {
                $mock = $this->createMock(QueueManager::class);
                $mock->method('pushCommand')->with(
                    $this->callback(function ($command) use ($order) {
                        // Проверяем что в очередь прилетела команда с нужным объектом.
                        $this->assertEquals($order['id'], $command->obj->obj->getProperty('id'));
                        $this->assertEquals(
                            $order['initialVelocity'],
                            $command->obj->obj->getProperty('initialVelocity')
                        );

                        return true;
                    })
                );

                return $mock;
            },
        )->execute();

        IoC::resolve(
            IoC::IOC_REGISTER,
            AuthManager::class,
            function () {
                $mock = $this->createMock(AuthManager::class);
                $mock->method('checkAccessToObject')->willReturn(true);

                return $mock;
            },
        )->execute();

        // Даем понять интерпретатору какую команду надо создавать из литерала StartMove.
        // Здесь можно добавить конфиг.
        IoC::resolve(
            IoC::IOC_REGISTER,
            Interpreter::GET_COMMAND_CLASS,
            function ($actionName) {
                // Конфиг надо вынести в отдельный файл потому, что его необходимо будет дополнять.
                $config = [
                    'StartMove' => StartMove::class,
                ];

                return $config[$actionName];
            },
        )->execute();

        IoC::resolve(
            IoC::IOC_REGISTER,
            IGameOrder::class,
            function ($orderInfo) {
                /** @var IGameOrder $object */
                $order = AdapterCreator::create(IGameOrder::class, new UObject());

                $order->setId($orderInfo['id']);
                $order->setAction($orderInfo['action']);
                unset($orderInfo['id']);
                unset($orderInfo['action']);
                $order->setParams($orderInfo);

                return $order;
            }
        )->execute();

        /** @var IGameOrder $gameOrder */
        $gameOrder = IoC::resolve(IGameOrder::class, [$order]);
        $command = new InterpretOrderCommand($gameOrder);
        $command->execute();
    }
}
