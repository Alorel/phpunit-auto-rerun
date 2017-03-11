<?php

    namespace Alorel\PHPUnitRetryRunner\Fixtures\Runtime;

    use PHPUnit\Framework\TestCase;
    use PHPUnit_Retriable_TestCase;

    class FailerTest extends TestCase {

        private $count = 1;

        /**
         * @retryCount 5
         * @sleepTime  0
         * @dataProvider provider
         */
        function testExc() {
            $this->assertEquals(3, $this->count++);
        }
    }