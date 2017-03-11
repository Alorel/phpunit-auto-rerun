<?php

    namespace Alorel\PHPUnitRetryRunner\Fixtures;

    class SleepTimeFixture {

        /**
         * @sleepTime 5
         */
        function positiveInteger() {

        }

        /**
         * @sleepTime 0
         */
        function zero() {

        }

        /**
         * @sleepTime -1
         */
        function negativeInteger() {

        }

        /**
         * @sleepTime foo
         */
        function string() {

        }
    }