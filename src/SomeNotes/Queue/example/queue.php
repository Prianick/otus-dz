<?php

// Server с очередью и блокировкой.
// Когда кто-то пишет, очередь блокируется.
// Поднимает сокет и ждет соединения.
// Будет писать в этот сокет команду из очереди.

$queue = new SplQueue();

function startServer()
{
    $port = 20202;

    $socket = @stream_socket_server("tcp://localhost:$port", $errNo, $errStr);

    if (!$socket) {
        throw new Exception($errStr, $errNo);
    }
    echo 'Queue started' . PHP_EOL;

    stream_set_blocking($socket, 0);

    $i = 0;
    while ($clientSocket = stream_socket_accept($socket, 20000)) {
        $seconds = 5 - $i;
        $request = fread($clientSocket, 1024);
        echo $request . PHP_EOL;
        if ($seconds > 0) {
            sleep($seconds);
        }
        $treadId = explode("\n\r", $request)[1];
        echo "answer " . $treadId . PHP_EOL;
        fwrite($clientSocket, 'Локальное время ' . date('n/j/Y g:i:s a') . "\n" . $treadId . "\n");
        fclose($clientSocket);
        $i++;
    }
    fclose($socket);
}

startServer();
