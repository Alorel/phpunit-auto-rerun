<?php

    namespace Alorel\PHPUnitRetryRunner\Fixtures\Runtime;

    use PHPUnit_Retriable_TestCase;

    class FailerTest extends PHPUnit_Retriable_TestCase {

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