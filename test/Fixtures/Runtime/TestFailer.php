<?php

    namespace Alorel\PHPUnitRetryRunner\Fixtures\Runtime;

    use Alorel\PHPUnitRetryRunner\RetriableTestCase;

    class TestFailer extends RetriableTestCase {

        private $count = 1;

        /**
         * @retryCount 5
         * @sleepTime  0
         */
        function testExc() {
            $this->assertEquals(3, $this->count++);
        }
    }