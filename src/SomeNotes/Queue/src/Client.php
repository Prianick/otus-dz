<?php

namespace OtusDZ\Src\SomeNotes\Queue\src;

class Client
{
    public const MODE_GET_TASK = 'get_tasks';
    public const WAITING_NEW_TASKS = 'waiting_new_tasks';
    public const MODE_SILENT = 'silent';
    public const MODE_REGISTER = 'register';

    public int $pid;

    public string $currentMode;

    /** @var resource */
    public $queueSocket;

    /**
     * @param string $response
     */
    public function parseResponse(string $response)
    {
        [$headers, $body] = (new Protocol())->getHeadersAndBody($response);
        switch ($headers[QueueManager::HEADERS_ACTION_TYPE]) {
            case QueueManager::GET_COUNT_ACTION:
                if (preg_match('`Queue count: (\d)+`', $body, $matches) == 1) {
                    if ((int) $matches[1] == 0) {
                        $this->currentMode = self::WAITING_NEW_TASKS;
                    } else {
                        $this->currentMode = self::MODE_GET_TASK;
                    }
                } else {
                    $this->currentMode = self::WAITING_NEW_TASKS;
                }
                break;
            case QueueManager::GET_ACTION:
                if (empty($body)) {
                    $this->currentMode = self::WAITING_NEW_TASKS;
                } else {
                    $this->currentMode = self::MODE_GET_TASK;
                }
                break;
            default;
                $this->currentMode = self::WAITING_NEW_TASKS;
                break;
        }
    }

    public function getActualRequest()
    {
        switch ($this->currentMode) {
            case self::MODE_GET_TASK:
                return QueueManager::getInstance(1)->getPullRequest($this->pid);
            case self::MODE_REGISTER:
                return QueueManager::getInstance(1)->getRegisterConsumer($this->pid);
            case self::WAITING_NEW_TASKS:
                return QueueManager::getInstance(1)->getWaitingNewTasks($this->pid);
            default;
                return null;
        }
    }

    /**
     * @param string $response
     * @return false|int
     */
    public function isEnd(string $response)
    {
        return preg_match('`' . QueueManager::END_MSG_POINT . '`', $response) === 1;
    }

    public function startSocketClient(string $host, string $port)
    {
        $fp = stream_socket_client(
            $host . ':' . $port,
            $errno,
            $errstr,
            30,
            STREAM_CLIENT_ASYNC_CONNECT | STREAM_CLIENT_CONNECT,
        // STREAM_CLIENT_PERSISTENT | STREAM_CLIENT_CONNECT,
        );

        if (!$fp) {
            echo "$errstr ($errno)\n";
            die();
        }

        return $fp;
    }
}
