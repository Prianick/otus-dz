<?php

declare(ticks=1);

namespace OtusDZ\Src\Dz7Thread;

use OtusDZ\Src\Dz4MacroCommand\Commands\Command;
use OtusDZ\Src\Dz5IoC\IoC;
use SplQueue;
use Throwable;

class Runner
{
    /** @var SplQueue */
    public $queue;

    /**
     *
     */
    public function __construct()
    {
        $this->queue = IoC::resolve('Queue');
    }

    /**
     * @return mixed|null
     */
    public function getCommand()
    {
        if ($this->queue->count() > 0) {
            return $this->queue->dequeue();
        }

        return null;
    }

    /**
     * @param string $command
     */
    public function addCommand(string $command)
    {
        if (ThreadManager::$status !== ThreadManager::SOFT_STOP) {
            ThreadManager::log('command added');
            $this->queue->push($command);
        }
    }

    private function executeOne()
    {
        try {
            $command = $this->getCommand();
            if ($command instanceof Command) {
                $command->execute();
            } else {
                ThreadManager::log($command);
                sleep(5);
            }
            if ($this->queue->count() < 0) {
                sleep(5);
            }
        } catch (Throwable $e) {
            ThreadManager::log('ERROR ' . $e->getMessage());
        }
    }

    public function execute(): void
    {
        while (ThreadManager::isActive()) {
            $this->executeOne();
        }

        if (ThreadManager::$status === ThreadManager::SOFT_STOP) {
            while ($this->queue->count() > 0) {
                $this->executeOne();
            }
        }
    }
}
