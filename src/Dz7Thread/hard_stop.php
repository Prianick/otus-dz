<?php

use \OtusDZ\Src\Dz7Thread\ThreadManager;

include __DIR__ . '/../../vendor/autoload.php';

$pid = ThreadManager::getPid();

// $cmd = 'ps -A ';
// $output = [];
// exec($cmd, $output);
// echo implode(PHP_EOL, $output) . PHP_EOL;
//
// echo $pid . PHP_EOL;
if (ThreadManager::isAlreadyStarted()) {
    ThreadManager::invokeHardStop();
    echo 'hard stop invoked';
} else {
    echo 'not run';
}
echo PHP_EOL;

// $output = [];
// exec($cmd, $output);
// echo implode(PHP_EOL, $output) . PHP_EOL;
