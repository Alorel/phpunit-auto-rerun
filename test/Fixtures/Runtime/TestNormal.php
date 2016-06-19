<?php

    namespace Alorel\PHPUnitRetryRunner\Fixtures\Runtime;

    use Alorel\PHPUnitRetryRunner\RetriableTestCase;

    /**
     * These are just normal test cases - testing to see that they don't interfere
     *
     * @sleepTime  3
     * @retryCount 10
     */
    class TestNormal extends RetriableTestCase {

    }