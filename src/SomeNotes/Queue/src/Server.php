<?php

namespace OtusDZ\Src\SomeNotes\Queue\src;

use Closure;
use Exception;

class Server
{
    /** @var array Массив потоков */
    public static array $pool = [];

    /**
     * Функция запускает сервер
     *
     * Принимает запрос в формате
     *
     * threadId: 1
     *
     * Body(json)
     *
     * @throws Exception
     */
    function startServer2(string $host, int $port, Closure $callBack)
    {

        $socket = @stream_socket_server($host . ':' . $port, $errNo, $errStr);

        if (!$socket) {
            throw new Exception($errStr, $errNo);
        }
        echo 'Queue started' . PHP_EOL;

        stream_set_blocking($socket, 0);

        $connects = [];
        while (true) {
            $read = $connects;
            $read[] = $socket;
            $write = $except = null;

            if (stream_select($read, $write, $except, null) === false) {
                break;
            }

            if (in_array($socket, $read)) { //есть новое соединение
                $connect = stream_socket_accept($socket, -1); //принимаем новое соединение
                $connects[(int) $connect] = $connect; //добавляем его в список необходимых для обработки
                unset($read[array_search($socket, $read)]);
            }

            foreach($read as $connect) {//обрабатываем все соединения

                $request = fread($connect, 1024);
                echo $request . PHP_EOL;

                [$headers, $body] = (new Protocol())->getHeadersAndBody($request);
                $headers[QueueManager::HEADERS_CLIENT_SOCKET] = $connect;
                $result = $callBack($headers, $body);
                if ($result !== QueueManager::WAIT_ACTION) {
                    if (is_array($result)) {
                        foreach ($result as $connect) {
                            unset($connects[(int) $connect]);
                            fclose($connect);
                        }
                    } else {
                        unset($connects[(int) $connect]);
                        fclose($connect);  // если закрывать сокетное соединение, то клиент уходит в бесконечный цикл и выжирает всё ядро
                    }
                }

            }
        }
    }


        // while ($clientSocket = stream_socket_accept($socket, 20000)) {
        //     $request = fread($clientSocket, 1024);
        //     echo $request . PHP_EOL;
        //
        //     [$headers, $body] = $this->getHeadersAndBody($request);
        //     $headers[QueueManager::HEADERS_CLIENT_SOCKET] = $clientSocket;
        //     $callBack($headers, $body);
        //     $treadId = $headers[QueueManager::HEADERS_THREAD_ID];
        //     fwrite($clientSocket, 'Локальное время ' . date('n/j/Y g:i:s a') . "\n" . $treadId . "\n");
        //     // fclose($clientSocket);
        // }
        // fclose($socket);
    // }
}
