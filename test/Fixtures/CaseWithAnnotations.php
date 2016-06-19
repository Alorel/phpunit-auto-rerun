<?php

    namespace Alorel\PHPUnitRetryRunner\Fixtures;

    use Alorel\PHPUnitRetryRunner\RetriableTestCase;

    /**
     * @retryCount 15
     * @sleepTime  11
     */
    class CaseWithAnnotations extends RetriableTestCase {

    }