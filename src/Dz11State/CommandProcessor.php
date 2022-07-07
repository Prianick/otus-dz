<?php

namespace OtusDZ\Src\Dz11State;

use Finite\Loader\ArrayLoader;
use Finite\StatefulInterface;
use Finite\StateMachine\StateMachine;
use OtusDZ\Src\Dz5IoC\IoC;
use OtusDZ\Src\SomeNotes\Queue\src\QueueManager;

class CommandProcessor implements StatefulInterface
{
    public const HARD_STOP_STATE = 'hardStop';
    public const MOVE_TO_STATE = 'moveTo';
    public const USUAL_STATE = 'usual';

    public static $isActive = true;

    public $state;

    public StateMachine $sm;

    public QueueManager $queueManager;

    public function __construct()
    {
        $this->queueManager = IoC::resolve(QueueManager::class);

        $sm = new StateMachine();
        $loader = new ArrayLoader([
            'class' => CommandProcessor::class,
            'states' => [
                self::USUAL_STATE => ['type' => 'initial', 'properties' => []],
                self::MOVE_TO_STATE => ['type' => 'normal', 'properties' => []],
                self::HARD_STOP_STATE => ['type' => 'final', 'properties' => []],
            ],
            'transitions' => [
                HardStopCommand::class => ['from' => ['usual', 'moveTo'], 'to' => 'hardStop'],
                RunCommand::class => ['from' => ['moveTo', 'usual'], 'to' => 'usual'],
                MoveToCommand::class => ['from' => ['usual'], 'to' => 'moveTo'],
            ],
        ]);
        $loader->load($sm);
        $sm->setObject($this);
        $sm->initialize();
        $this->sm = $sm;
    }

    public function run()
    {
        $currentCommand = $this->queueManager->dequeue();
        $currentCommand->execute();
        $this->sm->apply(get_class($currentCommand));

        if ($this->sm->getCurrentState() == 'hardStop') {
            self::$isActive = false;
        }
    }

    public function setFiniteState($state)
    {
        $this->state = $state;
    }

    public function getFiniteState()
    {
        return $this->state;
    }
}
