<?php

use OtusDZ\Src\SomeNotes\Queue\src\QueueManager;

/**
 * Создает задачи и отправляет их в очередь ./queue.php
 */
require_once __DIR__ . DIRECTORY_SEPARATOR . 'bootstrap.php';

function startSocketClient(string $host, string $port)
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

$host = 'tcp://localhost';
$port = 20202;
while (true) {
    $fp = startSocketClient($host, $port);

    $pid = getmypid();
    $request = QueueManager::getInstance($pid)->getPushRequest($pid, ['task ' . random_int(0, 9999)]);
    print_r($request);
    $result = fwrite($fp, $request);

    sleep(1);
    fclose($fp);
}
