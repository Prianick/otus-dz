<?php

$scaningDir = __DIR__ . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR;
echo $scaningDir;
function requireDir($dir)
{
    $files = scandir($dir);
    foreach ($files as $file) {
        if (preg_match('`\.php$`', $file) === 1) {
            require_once $dir . DIRECTORY_SEPARATOR . $file;
        } elseif (!in_array($file, ['.', '..'])) {
            requireDir($dir . DIRECTORY_SEPARATOR . $file);
        }
    }

}

requireDir($scaningDir);


