<?php

    namespace Alorel\PHPUnitRetryRunner\Runtime;

    use Alorel\PHPUnitRetryRunner\Fixtures\Runtime\ExceptionThrower;
    use Alorel\PHPUnitRetryRunner\Fixtures\Runtime\TestFailer;
    use ReflectionClass as RC;

    class RetriesTest extends \PHPUnit_Framework_TestCase {

        function testException() {
            exec(RUNTIME_BOOTSTRAP . (new RC(ExceptionThrower::class))->getFileName(), $out, $ret);

            $this->assertEquals(0, $ret);
        }

        function testFailingTest() {
            exec(RUNTIME_BOOTSTRAP . (new RC(TestFailer::class))->getFileName(), $out, $ret);

            $this->assertEquals(0, $ret);
        }
    }