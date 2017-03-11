<?php

    namespace Alorel\PHPUnitRetryRunner;

    $vendorDir = __DIR__ . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR;

    /** @noinspection PhpIncludeInspection */
    require_once $vendorDir . 'autoload.php';

    if (!getenv('SUITE')) {
        putenv('SUITE=5');
    }

    define(
        'RUNTIME_BOOTSTRAP',
        '"' . $vendorDir . 'bin' . DIRECTORY_SEPARATOR . 'phpunit" --no-configuration --no-coverage --bootstrap "'
        . __FILE__ . '" --no-globals-backup '
    );

    spl_autoload_register(function ($class) {
        $class = str_replace('Alorel\PHPUnitRetryRunner\\', '', $class);
        $loc = __DIR__ . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . getenv('SUITE') . DIRECTORY_SEPARATOR .
               str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';

        if (file_exists($loc)) {
            /** @noinspection PhpIncludeInspection */
            require_once $loc;
        }
    });