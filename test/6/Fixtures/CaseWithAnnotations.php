<?php

    namespace Alorel\PHPUnitRetryRunner\Fixtures;

    use PHPUnit\Framework\TestCase;

    /**
     * @retryCount 15
     * @sleepTime  11
     */
    class CaseWithAnnotations extends TestCase {

        /**
         * @sleepTime 100
         */
        function sleep() {

        }

        /**
         * @retryCount 100
         */
        function retry() {

        }

        function noAnno() {

        }

        /**
         * @sleepTime  100
         * @retryCount 100
         */
        function sleepRetry() {

        }
    }