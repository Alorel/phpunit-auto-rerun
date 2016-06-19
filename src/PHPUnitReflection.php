<?php

    namespace Alorel\PHPUnitRetryRunner;

    use PHPUnit_Framework_TestCase as Test;
    use ReflectionProperty as Prop;

    final class PHPUnitReflection {

        /**
         * @var Prop
         */
        private static $status;

        /**
         * @var Prop
         */
        private static $msg;

        static function getStatus() {
            return self::$status;
        }

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
