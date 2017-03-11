<?php

    namespace Alorel\PHPUnitRetryRunner\Source\General;

    use Alorel\PHPUnitRetryRunner\Fixtures\CaseWithAnnotations;
    use Alorel\PHPUnitRetryRunner\Fixtures\CaseWithoutAnnotations;
    use PHPUnit\Framework\TestCase;
    use PHPUnit_Retriable_TestCase;

    class RetriableTestCaseTest extends TestCase {

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
            self::$rAnnotations = new \ReflectionClass(PHPUnit_Retriable_TestCase::class);

            self::$rAnnotationsSleepTime = self::$rAnnotations->getProperty('sleepTime');
            self::$rAnnotationsSleepTime->setAccessible(true);

            self::$rAnnotationsRetryCount = self::$rAnnotations->getProperty('retryCount');
            self::$rAnnotationsRetryCount->setAccessible(true);

            self::$rParseMethodAnnotations = self::$rAnnotations->getMethod('parseMethodAnnotations');
            self::$rParseMethodAnnotations->setAccessible(true);

            self::$rName = new \ReflectionProperty(\PHPUnit_Framework_TestCase::class, 'name');
            self::$rName->setAccessible(true);
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

        /** @dataProvider pCountsOnConstruct */
        function testCountsOnConstruct($fixtureClass, $expectedRetries, $expectedSleepTime) {
            $o = new $fixtureClass();

            $this->assertEquals($expectedRetries, self::$rAnnotationsRetryCount->getValue($o));
            $this->assertEquals($expectedSleepTime, self::$rAnnotationsSleepTime->getValue($o));
        }

        function pCountsOnConstruct() {
            yield [CaseWithAnnotations::class, 15, 11];

            $o = new PHPUnit_Retriable_TestCase();
            $sleep = new \ReflectionProperty(PHPUnit_Retriable_TestCase::class, 'sleepTime');
            $retry = new \ReflectionProperty(PHPUnit_Retriable_TestCase::class, 'retryCount');

            $sleep->setAccessible(true);
            $retry->setAccessible(true);

            yield [
                CaseWithoutAnnotations::class,
                $retry->getValue($o),
                $sleep->getValue($o)
            ];
        }

        function testParamPassing() {
            $x = new PHPUnit_Retriable_TestCase('foo');
            $this->assertEquals('foo', $x->getName(false));
        }
    }
