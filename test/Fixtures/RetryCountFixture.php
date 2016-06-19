<?php

    namespace Alorel\PHPUnitRetryRunner\Fixtures;

    class RetryCountFixture {

        /**
         * @retryCount 5
         */
        function positiveInteger() {

        }

        /**
         * @retryCount 0
         */
        function zero() {

        }

        /**
         * @retryCount -1
         */
        function negativeInteger() {

        }

        /**
         * @retryCount foo
         */
        function string() {

        }
    }