<?php

    namespace Alorel\PHPUnitRetryRunner\Source\General;

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
        /**
         * @var \ReflectionProperty
         */
        private static $rName;

        /**
         * @var \ReflectionMethod
         */
        private static $rParseMethodAnnotations;

        public static function setUpBeforeClass() {
            self::$rAnnotations = new \ReflectionClass(RetriableTestCase::class);

            self::$rAnnotationsSleepTime = self::$rAnnotations->getProperty('sleepTime');
            self::$rAnnotationsSleepTime->setAccessible(true);

            self::$rAnnotationsRetryCount = self::$rAnnotations->getProperty('retryCount');
            self::$rAnnotationsRetryCount->setAccessible(true);

            self::$rParseMethodAnnotations = self::$rAnnotations->getMethod('parseMethodAnnotations');
            self::$rParseMethodAnnotations->setAccessible(true);

            self::$rName = new \ReflectionProperty(\PHPUnit_Framework_TestCase::class, 'name');
            self::$rName->setAccessible(true);
        }

        /** @dataProvider pCountsOnConstruct */
        function testCountsOnConstruct($fixtureClass, $expectedRetries, $expectedSleepTime) {
            $o = new $fixtureClass();

            $this->assertEquals($expectedRetries, self::$rAnnotationsRetryCount->getValue($o));
            $this->assertEquals($expectedSleepTime, self::$rAnnotationsSleepTime->getValue($o));
        }

        /** @dataProvider pMethodAnnotations */
        function testMethodAnnotations($method, $expectedSleep, $expectedRetry) {
            $actualSleep = null;
            $actualRetry = null;

            $obj = new CaseWithAnnotations();
            self::$rName->setValue($obj, $method);

            self::$rParseMethodAnnotations->invokeArgs($obj, [&$actualSleep, &$actualRetry]);

            $this->assertEquals($expectedSleep, $actualSleep);
            $this->assertEquals($expectedRetry, $actualRetry);
        }

        function pMethodAnnotations() {
            yield 'sleep()' => ['sleep', 100, 15];
            yield 'retry()' => ['retry', 11, 100];
            yield 'sleepRetry()' => ['sleepRetry', 100, 100];
            yield 'noAnno()' => ['noAnno', 11, 15];
        }

        function pCountsOnConstruct() {
            yield [CaseWithAnnotations::class, 15, 11];
            yield [CaseWithoutAnnotations::class, 0, 3];
        }
    }
