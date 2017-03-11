<?php

    namespace Alorel\PHPUnitRetryRunner\Source\Annotations\Tags;

    use Alorel\PHPUnitRetryRunner\Annotations\DocBlockFactoryManager;
    use Alorel\PHPUnitRetryRunner\Annotations\Tags\RetryCount as TagClass;
    use Alorel\PHPUnitRetryRunner\Fixtures\RetryCountFixture as Fix;
    use Alorel\PHPUnitRetryRunner\RetriableTestCase;

    class RetryCountTest extends RetriableTestCase {

        /** @dataProvider provideGoodConstructor */
        function testGoodConstructor($method) {
            $docblock = (new \ReflectionMethod(Fix::class, $method))->getDocComment();
            $factory = DocBlockFactoryManager::getFactory()->create($docblock);
            $tag = $factory->getTagsByName(TagClass::NAME)[0];

            $this->assertTrue($factory->hasTag(TagClass::NAME));
            $this->assertInstanceOf(TagClass::class, $tag);
            $this->assertTrue(is_numeric((string)$tag));
        }

        /**
         * @dataProvider             provideBadConstructor
         * @expectedException \InvalidArgumentException
         * @expectedExceptionMessage The @retryCount tag value must be an integer >= 0
         */
        function testBadConstructor($method) {
            $docblock = (new \ReflectionMethod(Fix::class, $method))->getDocComment();
            DocBlockFactoryManager::getFactory()->create($docblock);
        }

        function provideGoodConstructor() {
            yield ['positiveInteger'];
            yield ['zero'];
        }

        function provideBadConstructor() {
            yield ['negativeInteger'];
            yield ['string'];
        }
    }
