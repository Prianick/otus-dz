<?php

use \OtusDZ\Src\SomeNotes\Queue\src\Client;

/**
 * Достает задачи из очереди и должен, в будущем запускать их обработку.
 */
require_once __DIR__ . DIRECTORY_SEPARATOR . 'bootstrap.php';

$host = 'tcp://localhost';
$port = 20202;
$pid = getmypid();

$client = new Client();
$client->pid = $pid;
$client->currentMode = Client::MODE_REGISTER;
$fp = $client->startSocketClient($host, $port);
$client->queueSocket = $fp;

$socks = [];
$socks[(int) $fp] = $fp;

while (!empty($socks)) {
    $rSocks = [];
    $wSocks = [];
    $eSocks = [];
    foreach ($socks as $sock) {
        $rSocks[] = $sock;
    }
    $request = $client->getActualRequest();
    if ($request != null) {
        print_r($request);
        $result = fwrite($fp, $request);
    }
    echo "WAIT...";
    sleep(3);
    print_r(stream_get_meta_data($fp));
    if (!stream_select($rSocks, $wSocks, $eSocks, 200, 200)) {
        break;
    }

    foreach ($rSocks as $readySock) {
        echo 'RRR_SOCK' . PHP_EOL;
        $response = [];
        while (!feof($fp)) {
            $responsePart = fgets($fp, 1024);
            $response[] = $responsePart;
            echo $responsePart;
            if ($client->isEnd($responsePart)) {
                break;
            }
        }
        if (empty($response)) {
            echo "EMPTY response";
            $socks = [];
            break;
        }
        $client->parseResponse(implode('', $response));
        unset($socks[(int) $readySock]);
        $fp = $client->startSocketClient($host, $port);
        $socks[(int) $fp] = $fp; //открываем новое соединение
    }

    // foreach ($wSocks as $readySock) {
    //     echo 'WWW_SOCK' . PHP_EOL;
    //     $response = [];
    //     while (!feof($fp)) {
    //         $responsePart = fgets($fp, 1024);
    //         $response[] = $responsePart;
    //         echo $responsePart;
    //         if ($client->isEnd($responsePart)) {
    //             break;
    //         }
    //     }
    //     $client->parseResponse($response);
    //     $request = $client->getActualRequest();
    //     print_r($request);
    //     $result = fwrite($fp, $request);
    //     $rSocks[] = $socks[(int) $readySock];
    // }
}
