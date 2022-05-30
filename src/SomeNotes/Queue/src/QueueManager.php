<?php

namespace OtusDZ\Src\SomeNotes\Queue\src;

use SplQueue;

class QueueManager
{
    const PUSH_ACTION = 'push';
    const GET_ACTION = 'dequeue';
    const WAIT_ACTION = 'wait';
    const GET_COUNT_ACTION = 'getCount';
    const HEADERS_THREAD_ID = 'ThreadId';
    const HEADERS_ACTION_TYPE = 'ActionType';
    const HEADERS_CLIENT_SOCKET = 'ClientSocket';
    const END_MSG_POINT = PHP_EOL . '-===THE END===-' . PHP_EOL . PHP_EOL;
    const DIRECTION_HEADER = 'Direction';
    const RESPONSE = 'response';
    const REQUEST = 'request';
    const BORDER = PHP_EOL . PHP_EOL;

    /** @var QueueManager */
    static $instances = [];

    /** @var SplQueue */
    public $queue;

    private function __construct()
    {
        $this->queue = new SplQueue();
    }

    /**
     * @param int $threadId
     * @return QueueManager
     */
    public static function getInstance(int $threadId): QueueManager
    {
        if (empty(self::$instances[$threadId])) {
            $instance = new self();
            self::$instances[$threadId] = $instance;
        } else {
            $instance = self::$instances[$threadId];
        }

        return $instance;
    }

    public function push($data)
    {
        $this->queue->push($data);
    }

    public function dequeue()
    {
        return $this->queue->dequeue();
    }

    private function getStandardHeaders(int $threadId, string $action): array
    {
        return [
            self::DIRECTION_HEADER . ": " . self::REQUEST,
            self::HEADERS_ACTION_TYPE . ": " . $action,
            self::HEADERS_THREAD_ID . ": " . $threadId,
        ];
    }

    public function wrapRequest(array $headers, ?string $body = ''): string
    {
        return implode("\n", $headers) . self::BORDER . $body . self::END_MSG_POINT;
    }

    public function getPushRequest($threadId, $data): string
    {
        return $this->wrapRequest(
            $this->getStandardHeaders($threadId, self::PUSH_ACTION),
            json_encode($data),
        );
    }

    public function getRegisterConsumer($threadId): string
    {
        return $this->wrapRequest(
            $this->getStandardHeaders($threadId, self::GET_COUNT_ACTION)
        );
    }

    public function getWaitingNewTasks($threadId): string
    {
        return $this->wrapRequest(
            $this->getStandardHeaders($threadId, self::WAIT_ACTION)
        );
    }

    public function getPullRequest($threadId): string
    {
        return $this->wrapRequest(
            $this->getStandardHeaders($threadId, self::GET_ACTION)
        );
    }
}
