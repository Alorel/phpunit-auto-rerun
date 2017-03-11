<?php

    namespace Alorel\PHPUnitRetryRunner\Fixtures\Runtime;

    use PHPUnit\Framework\TestCase;

    class StillFailingTest extends TestCase {

        /**
         * @sleepTime  0
         * @retryCount 1
         */
        function testStillFails() {
            $this->assertTrue(false);
        }
    }