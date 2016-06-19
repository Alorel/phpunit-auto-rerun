<?php

    namespace Alorel\PHPUnitRetryRunner\General;

    use Alorel\PHPUnitRetryRunner\Fixtures\CaseWithAnnotations;
    use Alorel\PHPUnitRetryRunner\Fixtures\CaseWithoutAnnotations;
    use Alorel\PHPUnitRetryRunner\RetriableTestCase;

    class RetriableTestCaseTest extends \PHPUnit_Framework_TestCase {

        /**
         * @var \ReflectionClass
         */
        private static $rAnnotations;
        /**
         * @var \ReflectionProperty
         */
        private static $rAnnotationsSleepTime;
        /**
         * @var \ReflectionProperty
         */
        private static $rAnnotationsRetryCount;

        public static function setUpBeforeClass() {
            self::$rAnnotations = new \ReflectionClass(RetriableTestCase::class);

            self::$rAnnotationsSleepTime = self::$rAnnotations->getProperty('sleepTime');
            self::$rAnnotationsSleepTime->setAccessible(true);

            self::$rAnnotationsRetryCount = self::$rAnnotations->getProperty('retryCount');
            self::$rAnnotationsRetryCount->setAccessible(true);
        }

        function testThisReflect() {
            $prop = self::$rAnnotations->getProperty('thisReflect');
            $prop->setAccessible(true);

            $this->assertEquals(
                new \ReflectionClass(CaseWithAnnotations::class),
                $prop->getValue(new CaseWithAnnotations())
            );
        }

        /** @dataProvider pCountsOnConstruct */
        function testCountsOnConstruct($fixtureClass, $expectedRetries, $expectedSleepTime) {
            $o = new $fixtureClass();

            $this->assertEquals($expectedRetries, self::$rAnnotationsRetryCount->getValue($o));
            $this->assertEquals($expectedSleepTime, self::$rAnnotationsSleepTime->getValue($o));
        }

        function pCountsOnConstruct() {
            yield [CaseWithAnnotations::class, 15, 11];
            yield [CaseWithoutAnnotations::class, 0, 3];
        }
    }
