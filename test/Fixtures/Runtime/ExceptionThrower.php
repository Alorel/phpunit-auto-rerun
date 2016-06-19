<?php

    namespace Alorel\PHPUnitRetryRunner\Fixtures\Runtime;

    use Alorel\PHPUnitRetryRunner\RetriableTestCase;

    class ExceptionThrower extends RetriableTestCase {

        private $count = 1;

        /**
         * @retryCount 5
         * @sleepTime  0
         */
        function testExc() {
            var_dump($this->count);
            try {
                $this->assertEquals(0, $this->count % 3);
            } finally {
                $this->count++;
            }
        }
    }