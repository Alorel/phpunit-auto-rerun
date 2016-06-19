<?php

    namespace Alorel\PHPUnitRetryRunner\Annotations;

    use Alorel\PHPUnitRetryRunner\Annotations\Tags\RetryCount;
    use Alorel\PHPUnitRetryRunner\Annotations\Tags\SleepTime;
    use phpDocumentor\Reflection\DocBlockFactory;
    use ReflectionProperty;

    /**
     * Makes sure we only perform instantiation of DocBlockFactory once
     *
     * @author Art <a.molcanovas@gmail.com>
     */
    final class DocBlockFactoryManager {

        /**
         * The constructed factory
         *
         * @var DocBlockFactory
         */
        private static $FACTORY;

        /**
         * Return the DocBlockFactory instance
         *
         * @author Art <a.molcanovas@gmail.com>
         * @return DocBlockFactory
         */
        public static final function getFactory() {
            return self::$FACTORY;
        }
    }

    $ref = new ReflectionProperty(DocBlockFactoryManager::class, 'FACTORY');
    $ref->setAccessible(true);
    $ref->setValue(null,
                   DocBlockFactory::createInstance([
                                                       RetryCount::NAME => RetryCount::class,
                                                       SleepTime::NAME  => SleepTime::class
                                                   ]));