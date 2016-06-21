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

        /**
         * How many seconds to sleep between retries
         *
         * @var int
         */
        private $sleepTime = 0;

        /**
         * Maximum number of test attempts. 0 simply invokes the standard test runner, 1 runs a test case once
         * without retries, >=2 will apply retries on failure.
         *
         * @var int
         */
        private $retryCount = 0;

        /**
         * A {@link ReflectionClass} of $this
         *
         * @var ReflectionClass
         */
        private $thisReflect;

        /**
         * Message shown on retries
         *
         * @var string
         */
        private static $retryMsg = '%s failed; waiting for %d seconds before retry %d/%d' . PHP_EOL;

        /**
         * PHPUnit_Retriable_TestCase constructor. Receives the same arguments as
         * {@link PHPUnit_Framework_TestCase::__construct() the standard PHPUnit constructor}.
         *
         * @author Art <a.molcanovas@gmail.com>
         *
         * @param string $name
         * @param array  $data
         * @param string $dataName
         */
        function __construct($name = null, array $data = [], $dataName = '') {
            $this->thisReflect = new ReflectionClass($this);

            if ($doc = $this->thisReflect->getDocComment()) {
                $parsed = DocBlockFactoryManager::getFactory()->create($doc);

                if ($parsed->hasTag(SleepTime::NAME)) {
                    $this->sleepTime = (int)$parsed->getTagsByName(SleepTime::NAME)[0]->__toString();
                }
                if ($parsed->hasTag(RetryCount::NAME)) {
                    $this->retryCount = (int)$parsed->getTagsByName(RetryCount::NAME)[0]->__toString();
                }
            }

            parent::__construct($name, $data, $dataName);
        }

        /**
         * Parses the sleepTime and retryCount annotations for the given method
         *
         * @author Art <a.molcanovas@gmail.com>
         *
         * @param string $sleepTime  Reference to the variable which will hold the computed sleep time
         * @param string $retryCount Reference to the variable which will hold the computed retry count
         */
        private function parseMethodAnnotations(&$sleepTime, &$retryCount) {
            $sleepTime = $this->sleepTime;
            $retryCount = $this->retryCount;

            if ($doc = $this->thisReflect->getMethod($this->getName(false))->getDocComment()) {
                $parsed = DocBlockFactoryManager::getFactory()->create($doc);

                if ($parsed->hasTag(SleepTime::NAME)) {
                    $sleepTime = (int)$parsed->getTagsByName(SleepTime::NAME)[0]->__toString();
                }
                if ($parsed->hasTag(RetryCount::NAME)) {
                    $retryCount = (int)$parsed->getTagsByName(RetryCount::NAME)[0]->__toString();
                }
            }
        }

        /**
         * Runs the bare test sequence.
         *
         * @author             Art <a.molcanovas@gmail.com>
         * @throws Exception
         * @codeCoverageIgnore Won't be picked up as these are tested in separate processes
         * @uses               PHPUnit_Framework_TestCase::runBare()
         */
        public function runBare() {
            $this->parseMethodAnnotations($sleepTime, $retryCount);

            if ($retryCount > 0) {
                $numRuns = 0;

                while ($numRuns < $retryCount) {
                    $numRuns++;

                    try {
                        parent::runBare();

                        return;
                    } catch (Exception $e) {
                        if ($numRuns == $retryCount) {
                            throw $e;
                        } else {
                            PHPUnitReflection::getMessage()->setValue($this, '');
                            PHPUnitReflection::getStatus()->setValue($this, null);

                            fwrite(
                                STDERR,
                                sprintf(self::$retryMsg, $this->getName(false), $sleepTime, $numRuns, $retryCount)
                            );

                            sleep($sleepTime);
                        }
                    }
                }
            } else {
                parent::runBare();
            }
        }
    }