<?php

namespace OtusDZ\Src\Dz8Messaging;

use OtusDZ\Src\Dz4MacroCommand\Commands\Command;
use OtusDZ\Src\Dz5IoC\IoC;

class InterpretCommand implements Command
{
    /** @var Command */
    public Command $builtCommand;

    /** @var Message */
    public Message $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    public function execute()
    {
        /** @var AccessMiddleware $accessMiddleware */
        $accessMiddleware = IoC::resolve(AccessMiddleware::class, []);
        /** @var RulesManager $rulesManager */
        $rulesManager = IoC::resolve(RulesManager::class, []);

        $command = $rulesManager->getCommand(
            $accessMiddleware->getPlayerType($this->message->getAuthToken()),
            $this->message->getOperationAlias(),
            ['objectId' => $this->message->getObjetId()],
        );

        /** @var QueueManager $queueManager */
        $queueManager = IoC::resolve(QueueManager::class);
        $queueManager->setGameId($this->message->getGameId());
        $queueManager->pushCommand($command);
    }
}
