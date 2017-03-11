<?php

    namespace Alorel\PHPUnitRetryRunner\Fixtures\Runtime;

    use PHPUnit\Framework\TestCase;

    class ExceptionThrowerTest extends TestCase {

        private $count = 1;

        /**
         * @retryCount   5
         * @sleepTime    0
         */
        function testExc() {
            $this->assertEquals(0, ($this->count++) % 3);
        }
    }