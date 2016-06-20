<?php

    namespace Alorel\PHPUnitRetryRunner\Fixtures\Runtime;

    use PHPUnit_Retriable_TestCase;

    class StillFailingTest extends PHPUnit_Retriable_TestCase {

        /**
         * @sleepTime  0
         * @retryCount 3
         */
        function testStillFails() {
            $this->assertTrue(false);
        }
    }