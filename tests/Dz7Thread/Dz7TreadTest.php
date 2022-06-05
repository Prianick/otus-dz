<?php

namespace Dz7Thread;

use OtusDZ\Src\Dz4MacroCommand\Commands\Command;
use OtusDZ\Src\Dz5IoC\IoC;
use OtusDZ\Src\Dz5IoC\IoCRegister;
use OtusDZ\Src\Dz7Thread\Runner;
use OtusDZ\Src\Dz7Thread\ThreadManager;
use PHPUnit\Framework\TestCase;
use SplQueue;

class Dz7TreadTest extends TestCase
{
    public string $commandDirectory;

    public function setUp(): void
    {
        $this->commandDirectory = __DIR__ . '/../../src/Dz7Thread/';
        parent::setUp();
    }

    public function testCommand()
    {
        // $filePath = $this->commandDirectory . 'start.php';
        // $startCmd = 'nohup php ' . $filePath . ' > /dev/null &';
        // $hardStopCmd = 'nohup php ' . $this->commandDirectory . 'hard_stop.php  > /dev/null &';
        // // $softStopCmd = 'nohup php ' . $this->commandDirectory . 'soft_stop.php  > /dev/null &';
        // $outPut = [];
        // exec($startCmd, $outPut);
        // $status = ThreadManager::getStatus();
        // sleep(4);
        // $this->assertNotEmpty($status[1]);
        // $this->assertStringContainsString($filePath, $status[1]);
        // exec($hardStopCmd, $outPut);
        // sleep(4);
        // $status = ThreadManager::getStatus();
        $this->assertTrue(empty($status[1]));
    }

    public function testSoftStop()
    {
        IoC::resolve(IoC::IOC_REGISTER, IoC::IOC_REGISTER, static function () {
            return new IoCRegister();
        })->execute();
        IoC::resolve(IoC::IOC_REGISTER, 'Queue', function () {
            $queue = new SplQueue();

            for ($i = 0; $i < 4; $i++) {
                $command = $this->createMock(Command::class);
                $command->expects($this->once())->method('execute');
                $queue->push($command);
            }

            return $queue;
        })->execute();

        ThreadManager::start();
        ThreadManager::setSignalHandlers();
        ThreadManager::softStop();

        $runner = new Runner();
        $runner->execute();
    }
}



