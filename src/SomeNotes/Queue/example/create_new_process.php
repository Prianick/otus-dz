<?php

$i = 0;
while ($i < 10) {
    $cmd = 'php ' . __DIR__ . DIRECTORY_SEPARATOR . 'newProcess.php > /dev/null &';
    exec($cmd);
    echo $cmd . PHP_EOL;
    $i++;
}
