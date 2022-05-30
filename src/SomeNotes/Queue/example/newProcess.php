<?php

declare(ticks=1);

$pid = pcntl_fork();
if ($pid == -1) {
    die("Не удалось породить процесс");
} elseif ($pid) {
    exit(); // это код родителя
} else {
    echo "started";
}

// Открепление от управляющего терминала
if (posix_setsid() == -1) {
    die("Не удалось открепить от терминала");
}

// Установка обработчиков сигналов
pcntl_signal(SIGTERM, "sig_handler");
pcntl_signal(SIGHUP, "sig_handler");

function logg(string $text)
{
    $f = fopen('./main.log', 'a+');
    fwrite($f, $text . PHP_EOL);
    fclose($f);
}

// Бесконечный цикл выполнения задач
$i = 0;
while (1) {
    logg('text ' . $i);
    $i++;
}

function sig_handler($signo)
{

    switch ($signo) {
        case SIGTERM:
            // обработка сигнала завершения
            exit;
            break;
        case SIGHUP:
            // обработка перезапуска задач
            break;
        default:
            // обработка других сигналов
    }

}

