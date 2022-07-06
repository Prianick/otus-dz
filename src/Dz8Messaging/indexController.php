<?php

namespace OtusDZ\Src\Dz8Messaging;

use OtusDZ\Src\Dz5IoC\IoC;

class indexController
{
    /**
     * @param Message $message
     */
    public function indexAction(Message $message)
    {
        $command = new InterpretCommand($message);
        /** @var QueueManager $queueManager */
        $queueManager = IoC::resolve(QueueManager::class, ['queueId' => $message->getGameId()]);
        $queueManager->pushCommand($command);
        $this->sendResponse();
    }

    public function sendResponse()
    {

        // close socket connection
    }
}
