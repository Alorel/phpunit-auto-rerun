<?php

    namespace Alorel\PHPUnitRetryRunner\Fixtures\Runtime;

    use PHPUnit\Framework\TestCase;

    /**
     * @sleepTime  0
     * @retryCount 5
     */
    class DataProviderTest extends TestCase {

        private $count = 1;

        /** @dataProvider pArray */
        function testArray($zero) {
            $this->assertEquals($zero, ($this->count++) % 3);
        }

        /**
         * @dataProvider pGenerator
         * @depends      testArray
         */
        function testGenerator($zero) {
            $this->assertEquals($zero, ($this->count++) % 3);
        }

        function pArray() {
            return [
                'first'  => [0],
                'second' => [2]
            ];
        }

        function pGenerator() {
            yield 'first' => [0];
            yield 'second' => [2];
        }
    }