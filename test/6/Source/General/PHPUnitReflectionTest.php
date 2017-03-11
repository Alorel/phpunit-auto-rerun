<?php

    namespace Alorel\PHPUnitRetryRunner\Source\General;

    use Alorel\PHPUnitRetryRunner\PHPUnitReflection;
    use PHPUnit\Framework\TestCase;

    class PHPUnitReflectionTest extends TestCase {

        /** @dataProvider provide */
        function testCorrectReflection($method, $field) {
            $actualField = new \ReflectionProperty(\PHPUnit_Framework_TestCase::class, $field);
            $actualField->setAccessible(true);

            $this->assertEquals(
                (new \ReflectionMethod(PHPUnitReflection::class, $method))->invoke(null),
                $actualField
            );
        }

        function provide() {
            yield ['getStatus', 'status'];
            yield ['getMessage', 'statusMessage'];
        }
    }