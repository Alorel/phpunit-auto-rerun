<?php

    namespace Alorel\PHPUnitRetryRunner;

    use ReflectionProperty as Prop;

    /**
     * Utility class - contains accessible {@link PHPUnit_Framework_TestCase} properties
     *
     * @author Art <a.molcanovas@gmail.com>
     */
    final class PHPUnitReflection {

        /**
         * The test status
         *
         * @var Prop
         */
        private static $status;

        /**
         * The test message
         *
         * @var Prop
         */
        private static $msg;

        /**
         * Returns the status property
         *
         * @return Prop
         */
        static function getStatus() {
            return self::$status;
        }

        /**
         * Returns the message property
         *
         * @return Prop
         */
        static function getMessage() {
            return self::$msg;
        }
    }

    if (class_exists('\PHPUnit_Framework_TestCase')) {
        $class = '\PHPUnit_Framework_TestCase';
    } elseif (class_exists('\PHPUnit\Framework\TestCase')) {
        $class = '\PHPUnit\Framework\TestCase';
    } else {
        trigger_error('No PHPUnit framework class found', E_USER_ERROR);
        die(1);
    }

    $innerStatus = new Prop(PHPUnitReflection::class, 'status');
    $innerMsg = new Prop(PHPUnitReflection::class, 'msg');

    $outerStatus = new Prop($class, 'status');
    $outerMsg = new Prop($class, 'statusMessage');

    $innerStatus->setAccessible(true);
    $innerMsg->setAccessible(true);
    $outerStatus->setAccessible(true);
    $outerMsg->setAccessible(true);

    $innerMsg->setValue(null, $outerMsg);
    $innerStatus->setValue(null, $outerStatus);
