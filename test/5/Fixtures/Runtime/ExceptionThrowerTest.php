<?php

    namespace Alorel\PHPUnitRetryRunner\Fixtures\Runtime;

    use PHPUnit_Retriable_TestCase;

    class ExceptionThrowerTest extends PHPUnit_Retriable_TestCase {

        private $count = 1;

        /**
         * @retryCount   5
         * @sleepTime    0
         */
        function testExc() {
            $this->assertEquals(0, ($this->count++) % 3);
        }
    }