<?php

    namespace Alorel\PHPUnitRetryRunner;

    use PHPUnit_Framework_TestCase as Test;
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
*@var Prop
         */
        private static $status;

        /**
         * The test message
         * 
*@var Prop
         */
        private static $msg;

        /**
         * Returns the status property
         * @return Prop
         */
        static function getStatus() {
            return self::$status;
        }

        /**
         * Returns the message property
         * @return Prop
         */
        static function getMessage() {
            return self::$msg;
        }
    }

    $innerStatus = new Prop(PHPUnitReflection::class, 'status');
    $innerMsg = new Prop(PHPUnitReflection::class, 'msg');

    $outerStatus = new Prop(Test::class, 'status');
    $outerMsg = new Prop(Test::class, 'statusMessage');

    $innerStatus->setAccessible(true);
    $innerMsg->setAccessible(true);
    $outerStatus->setAccessible(true);
    $outerMsg->setAccessible(true);

    $innerMsg->setValue(null, $outerMsg);
    $innerStatus->setValue(null, $outerStatus);
