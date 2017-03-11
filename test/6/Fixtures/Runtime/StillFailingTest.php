<?php

    namespace Alorel\PHPUnitRetryRunner\Fixtures\Runtime;

    use Alorel\PHPUnitRetryRunner\RetriableTestCase;

    class StillFailingTest extends RetriableTestCase {

        /**
         * @sleepTime  0
         * @retryCount 1
         */
        function testStillFails() {
            $this->assertTrue(false);
        }
    }