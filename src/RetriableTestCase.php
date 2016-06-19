<?php

    namespace Alorel\PHPUnitRetryRunner;

    use Alorel\PHPUnitRetryRunner\Annotations\DocBlockFactoryManager;
    use Alorel\PHPUnitRetryRunner\Annotations\Tags\RetryCount;
    use Alorel\PHPUnitRetryRunner\Annotations\Tags\SleepTime;
    use Exception;
    use PHPUnit_Framework_TestCase;
    use ReflectionClass;

    class RetriableTestCase extends PHPUnit_Framework_TestCase {

        private $sleepTime = 3;

        private $retryCount = 0;

        private $thisReflect;

        function __construct(...$args) {
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

            parent::__construct(...$args);
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
                        if ($numRuns == $this->retryCount) {
                            throw $e;
                        } else {
                            PHPUnitReflection::getMessage()->setValue($this, '');
                            PHPUnitReflection::getStatus()->setValue($this, null);

                            fwrite(STDERR,
                                   $this->getName(false) . ' failed; waiting for ' . $this->sleepTime
                                   . ' seconds before retry # ' . $numRuns . '/' . $this->retryCount . PHP_EOL);

                            sleep($sleepTime);
                        }
                    }
                }
            } else {
                parent::runBare();
            }
        }
    }