<?php

namespace Dz11State;

use OtusDZ\Src\Dz11State\CommandProcessor;
use OtusDZ\Src\Dz11State\HardStopCommand;
use OtusDZ\Src\Dz11State\MoveToCommand;
use OtusDZ\Src\Dz11State\RunCommand;
use OtusDZ\Src\Dz5IoC\IoC;
use OtusDZ\Src\Dz5IoC\IoCRegister;
use OtusDZ\Src\SomeNotes\Queue\src\QueueManager;
use PHPUnit\Framework\TestCase;

class StateTest extends TestCase
{
    public QueueManager $queueMock;

    public function setUp(): void
    {
        $this->queueMock = $this->createMock(QueueManager::class);
        IoC::resolve(IoC::IOC_REGISTER, IoC::IOC_REGISTER, function () {
            return new IoCRegister();
        })->execute();
        IoC::resolve(IoC::IOC_REGISTER, QueueManager::class, function () {
            return $this->queueMock;
        })->execute();
        parent::setUp();
    }

    public function testHardStop()
    {
        $this->queueMock->method('dequeue')->willReturn(new HardStopCommand());
        $cp = new CommandProcessor();
        $cp->run();
        $this->assertEquals(CommandProcessor::HARD_STOP_STATE, $cp->sm->getCurrentState()->getName());
    }

    public function testRunCommand()
    {
        $this->queueMock->method('dequeue')->willReturn(new RunCommand());
        $cp = new CommandProcessor();
        $cp->run();
        $this->assertEquals(CommandProcessor::USUAL_STATE, $cp->sm->getCurrentState()->getName());
    }

    public function testMoveTo()
    {
        $this->queueMock->method('dequeue')->willReturn(new MoveToCommand());
        $cp = new CommandProcessor();
        $cp->run();
        $this->assertEquals(CommandProcessor::MOVE_TO_STATE, $cp->sm->getCurrentState()->getName());
    }
}
