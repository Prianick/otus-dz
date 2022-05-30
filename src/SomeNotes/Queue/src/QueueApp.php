<?php

namespace OtusDZ\Src\SomeNotes\Queue\src;

class QueueApp
{
    public QueueManager $queueManager;

    public function getTask(): string
    {
        $response = '';
        if ($this->queueManager->queue->count() > 0) {
            $task = $this->queueManager->dequeue();
            $response .= json_encode($task);
        } else {
            $response .= '';
        }

        return $response;
    }

    public function getCount(): int
    {
        return $this->queueManager->queue->count();
    }

    public function pushTaskToQueue($task)
    {
        $this->queueManager->queue->push($task);
    }

    public function pingConsumers(): array
    {
        $pinged = [];
        foreach (Server::$pool as $socket) {
            if($this->queueManager->queue->count() > 0){
                $this->sendResponse($socket, [], $this->queueManager->queue->dequeue());
                $pinged[(int) $socket] = $socket;
            }
        }

        return $pinged;
    }

    /**
     * @param $clientSocket
     * @param array $headers
     * @param string $body
     */
    public function sendResponse($clientSocket, array $headers, string $body)
    {
        $headerStr = [];
        $headers[QueueManager::DIRECTION_HEADER] = QueueManager::RESPONSE;
        foreach ($headers as $key => $header) {
            if (is_string($header)) {
                $headerStr[] = $key . ": " . rtrim($header, PHP_EOL);
            }
        }
        $query = implode("\n", $headerStr);
        $query .= QueueManager::BORDER;
        $query .= $body;
        $query .= QueueManager::END_MSG_POINT;
        print_r($query);
        fwrite($clientSocket, $query);
    }

    /**
     * @param array $headers
     * @param string|null $body
     * @return string
     */
    public function prepareResponse(array $headers, ?string $body = ''): string
    {
        $this->queueManager = QueueManager::getInstance(1);

        $clientSocket = $headers[QueueManager::HEADERS_CLIENT_SOCKET];

        if (empty($headers['ActionType'])) {
            // Если заголовок пустой, закрываем соединение и убиваем ссылку на сокет, чтобы не рассылать туда данные.
            unset(Server::$pool[(int) $clientSocket]);

            return '';
        }

        echo "\n";
        echo $headers['ActionType'];
        switch ($headers['ActionType']) {
            case QueueManager::GET_ACTION:
                echo "DEQUEUE" . PHP_EOL;
                $response = $this->getTask();
                $this->sendResponse($clientSocket, $headers, $response);
                break;
            case QueueManager::GET_COUNT_ACTION:
                $response = "Queue count: " . $this->getCount();
                $this->sendResponse($clientSocket, $headers, $response);
                break;
            case QueueManager::WAIT_ACTION:
                Server::$pool[(int) $headers[QueueManager::HEADERS_THREAD_ID]] = $clientSocket;

                return QueueManager::WAIT_ACTION;
            default:
                $this->pushTaskToQueue($body);
                $this->pingConsumers();
                break;
        }

        return '';
    }
}
