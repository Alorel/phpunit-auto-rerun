<?php

    use Alorel\PHPUnitRetryRunner\Annotations\DocBlockFactoryManager;
    use Alorel\PHPUnitRetryRunner\Annotations\Tags\RetryCount;
    use Alorel\PHPUnitRetryRunner\Annotations\Tags\SleepTime;

    class PHPUnit_Retriable_TestCase extends PHPUnit_Framework_TestCase {

        private $sleepTime = 3;

        private $retryCount = 0;

        private $thisReflect;

        /** @noinspection PhpMissingParentConstructorInspection */
        function __construct() {
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
            call_user_func_array('parent::__construct', func_get_args());
        }

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
         * @throws Exception
         * @codeCoverageIgnore Won't be picked up as these are tested in separate processes
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
                            \Alorel\PHPUnitRetryRunner\PHPUnitReflection::getMessage()->setValue($this, '');
                            \Alorel\PHPUnitRetryRunner\PHPUnitReflection::getStatus()->setValue($this, null);

                            fwrite(STDERR,
                                   $this->getName(false) . ' failed; waiting for ' . $sleepTime
                                   . ' seconds before retry # ' . $numRuns . '/' . $retryCount . PHP_EOL);

                            sleep($sleepTime);
                        }
                    }
                }
            } else {
                parent::runBare();
            }
        }
    }