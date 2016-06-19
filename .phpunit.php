<?php

    require_once __DIR__ . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

    spl_autoload_register(function ($class) {
        $class = str_replace('Alorel\PHPUnitRetryRunner\\', '', $class);
        $loc = __DIR__ . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR .
               str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';

        if (file_exists($loc)) {
            /** @noinspection PhpIncludeInspection */
            require_once $loc;
        }
    });