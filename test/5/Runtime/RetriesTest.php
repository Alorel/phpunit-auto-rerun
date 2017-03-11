<?php

    namespace Alorel\PHPUnitRetryRunner\Runtime;

    use Alorel\PHPUnitRetryRunner\Fixtures\Runtime\DataProviderTest;
    use Alorel\PHPUnitRetryRunner\Fixtures\Runtime\ExceptionThrowerTest;
    use Alorel\PHPUnitRetryRunner\Fixtures\Runtime\FailerTest;
    use Alorel\PHPUnitRetryRunner\Fixtures\Runtime\StillFailingTest;
    use ReflectionClass as RC;

    class RetriesTest extends \PHPUnit_Framework_TestCase {

        /** @dataProvider pTestExitZero */
        function testExitZero($class) {
            exec(RUNTIME_BOOTSTRAP . '"' . (new RC($class))->getFileName() . '"', $out, $ret);
            $this->assertEquals(0, $ret);
        }

        function pTestExitZero() {
            yield ExceptionThrowerTest::class => [ExceptionThrowerTest::class];
            yield FailerTest::class => [FailerTest::class];
            yield DataProviderTest::class => [DataProviderTest::class];
        }

        /** @dataProvider pTestFail */
        function testFail($class, ...$contains) {
            exec(RUNTIME_BOOTSTRAP . '"' . (new RC($class))->getFileName() . '"', $out, $ret);

            $this->assertNotEquals(0, $ret);
            foreach ($contains as $c) {
                $this->assertContains($c, $out);
            }
        }

        function pTestFail() {
            yield StillFailingTest::class => [
                StillFailingTest::class,
                'Tests: 1, Assertions: 1, Failures: 1.'
            ];
        }
    }