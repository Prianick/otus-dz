<?php

use OtusDZ\Src\SomeNotes\Queue\src\Server;
use OtusDZ\Src\SomeNotes\Queue\src\QueueApp;

/**
 * Очередь.
 * Запускает сервер и ждет входящих задач от ./provider.php.
 * Готов отдавать сообщения в ./consumer.php для обработки.
 */
require_once __DIR__ . DIRECTORY_SEPARATOR . 'bootstrap.php';

$app = function (array $headers, ?string $body = null): string {
    $queueApp = new QueueApp();
    return $queueApp->prepareResponse($headers, $body);
};

try {
    $server = new Server();
    $server->startServer2('tcp://localhost', 20202, $app);
} catch (Exception $e) {
    // todo: доделать обработку ошибок
    throw $e;
}
