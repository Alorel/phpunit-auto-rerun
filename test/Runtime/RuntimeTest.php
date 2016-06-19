<?php

    namespace Alorel\PHPUnitRetryRunner\Runtime;

    use Alorel\PHPUnitRetryRunner\Fixtures\Runtime\ExceptionThrower;
    use ReflectionClass as RC;

    class RuntimeTest extends \PHPUnit_Framework_TestCase {

        function testException() {
            exec(RUNTIME_BOOTSTRAP . (new RC(ExceptionThrower::class))->getFileName(),
                 $out,
                 $ret);

            $this->assertContains('OK (1 test, 3 assertions)', $out);
            $this->assertEquals(0, $ret);
        }
    }