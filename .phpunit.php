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

    define('PHPUNIT_AUTOLOAD_BASEDIR',
           __DIR__ . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . getenv('SUITE') .
           DIRECTORY_SEPARATOR);

    echo 'Base dir set to ' . PHPUNIT_AUTOLOAD_BASEDIR . PHP_EOL;

    spl_autoload_register(function ($class) {
        $class = str_replace('Alorel\PHPUnitRetryRunner\\', '', $class);
        $loc = PHPUNIT_AUTOLOAD_BASEDIR . str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';

        if (file_exists($loc)) {
            /** @noinspection PhpIncludeInspection */
            require_once $loc;
        }
    });