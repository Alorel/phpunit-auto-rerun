<?php

    use Alorel\PHPUnitRetryRunner\Annotations\DocBlockFactoryManager;
    use Alorel\PHPUnitRetryRunner\Annotations\Tags\RetryCount;
    use Alorel\PHPUnitRetryRunner\Annotations\Tags\SleepTime;
    use Alorel\PHPUnitRetryRunner\PHPUnitReflection;

    /**
     * Automatically reruns a failing test case up to n times. See link for full configuration instructions and
     * examples.
     *
     * @author Art <a.molcanovas@gmail.com>
     * @link https://github.com/Alorel/phpunit-auto-rerun/#configuration
     */
    class PHPUnit_Retriable_TestCase extends PHPUnit_Framework_TestCase {

        use \PHPUnit_Retriable_TestCase_Trait;

    }
