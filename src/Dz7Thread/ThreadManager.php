<?php

namespace OtusDZ\Src\Dz7Thread;

use DateTime;

class ThreadManager
{
    public const ACTIVE = 'active';
    public const HARD_STOP = 'hard_stop';
    public const SOFT_STOP = 'soft_stop';

    /** @var string */
    public static string $status;

    private static function getFileName()
    {
        return __DIR__ . DIRECTORY_SEPARATOR . 'var/pid.txt';
    }

    private static function getLogFilePath()
    {
        return __DIR__ . DIRECTORY_SEPARATOR . 'var/main.log';
    }

    public static function savePid()
    {
        $pid = getmypid();
        echo 'getmypid: ' . $pid . PHP_EOL;
        $pid = posix_getpid();
        echo 'posix_getpid: ' . $pid . PHP_EOL;
        file_put_contents(self::getFileName(), $pid);
    }

    public static function getPid()
    {
        return file_get_contents(self::getFileName());
    }

    public static function start()
    {
        self::$status = self::ACTIVE;
        self::savePid();
    }

    public static function hardStop()
    {
        self::$status = self::HARD_STOP;
        self::log(self::HARD_STOP);
    }

    public static function softStop()
    {
        self::$status = self::SOFT_STOP;
        self::log(self::SOFT_STOP);
    }

    public static function invokeSoftStop()
    {
        posix_kill(self::getPid(), SIGQUIT);
    }

    public static function invokeHardStop()
    {
        posix_kill(self::getPid(), SIGTERM);
    }

    public static function isActive()
    {
        if (in_array(self::$status, [self::HARD_STOP, self::SOFT_STOP])) {
            return false;
        }

        return true;
    }

    /**
     * @param $text
     * @return int|false
     */
    public static function log($text): int|false
    {
        $time = new DateTime();
        $logData = [
            $time->format(DateTime::ATOM),
            posix_getpid(),
            $text,
        ];

        return file_put_contents(
            self::getLogFilePath(),
            implode(' ', $logData) . PHP_EOL,
            FILE_APPEND,
        );
    }

    public static function getStatus()
    {
        $cmd = 'ps -f ' . self::getPid();
        $output = [];
        exec($cmd, $output);

        return $output;
    }

    /**
     * @return bool
     */
    public static function isAlreadyStarted(): bool
    {
        $cmd = 'ps -f ' . self::getPid();
        $output = [];
        exec($cmd, $output);

        return !empty($output[1])
            && preg_match('`start\.php`', $output[1]) !== false;
    }

    public static function setSignalHandlers()
    {
        $sigHandler = function ($signo) {
            switch ($signo) {
                case SIGTERM:
                    ThreadManager::hardStop();
                    exit;
                    break;
                case SIGQUIT:
                    ThreadManager::softStop();
                    break;
                default:
                    // обработка других сигналов
            }
        };

        // Установка обработчиков сигналов
        pcntl_signal(SIGTERM, $sigHandler);
        pcntl_signal(SIGQUIT, $sigHandler);
    }
}
