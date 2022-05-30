<?php

// читает из очереди сообщения если они там есть
// если выбрасывается исключение, отлавливает его и продолжает работать

$socks = [];

$getTime = function ($threadId) use (&$socks) {
    $port = 20202;
    $fp = @stream_socket_client(
        "tcp://localhost:$port",
        $errno,
        $errstr,
        30,
        STREAM_CLIENT_ASYNC_CONNECT | STREAM_CLIENT_CONNECT,
        // STREAM_CLIENT_PERSISTENT | STREAM_CLIENT_CONNECT,
    );

    // stream_set_timeout($fp, 20);

    echo 'connected' . PHP_EOL;
    if (!$fp) {
        echo "$errstr ($errno)<br />\n";
    } else {
        $text = yield;

        $socks[(int) $fp] = [$fp, $threadId];
        echo $text . PHP_EOL;
        fwrite($fp, "GET time \n\r thread id: {$threadId} \n\r text {$text}" . PHP_EOL);

        yield;

        while (!feof($fp)) {
            echo fgets($fp, 1024);
        }
        unset($socks[(int) $fp]);
        fclose($fp);
    }
};

$coroutines = [];

$i = 0;
while ($i < 5) {
    $coroutine = $getTime($i);
    $coroutine->send('some text ' . $i);
    $coroutines[$i] = $coroutine;
    echo 'after fgets' . PHP_EOL;
    $i++;
}

//Не обнулять эти массивы! Сбрасывается прослушивание.
$wSocks = [];
$eSocks = [];
while (!empty($socks)) {
    $rSocks = [];
    foreach ($socks as [$sock, $treadId]) {
        $rSocks[] = $sock;
    }
    // чтобы переключаться между режимами ожидания, нужно перезапускать и клиент и сервер
    stream_select($rSocks, $wSocks, $eSocks, 200, 200);

    foreach ($rSocks as $readySock) {
        $coroutines[$socks[(int) $readySock][1]]->next();
    }
}



