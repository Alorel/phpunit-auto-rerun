<?php

    namespace Alorel\PHPUnitRetryRunner\Fixtures;

    use PHPUnit_Retriable_TestCase;

    /**
     * @retryCount 15
     * @sleepTime  11
     */
    class CaseWithAnnotations extends PHPUnit_Retriable_TestCase {

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