<?php

    namespace Alorel\PHPUnitRetryRunner\Annotations\Tags;

    use phpDocumentor\Reflection\DocBlock\Description;
    use phpDocumentor\Reflection\DocBlock\DescriptionFactory;
    use phpDocumentor\Reflection\DocBlock\Tags\BaseTag;
    use phpDocumentor\Reflection\DocBlock\Tags\Factory\StaticMethod;
    use phpDocumentor\Reflection\Types\Context;
    use Webmozart\Assert\Assert;

    /**
     * The @sleepTime tag descriptor
     *
     * @author Art <a.molcanovas@gmail.com>
     */
    final class SleepTime extends BaseTag implements StaticMethod {

        /**
         * Tag name
         *
         * @var string
         */
        const NAME = 'sleepTime';

        /**
         * Error message
         *
         * @var string
         */
        const MSG = 'The @sleepTime tag value must be an integer >= 0';

        /**
         * Tag name
         *
         * @var string
         */
        protected $name = self::NAME;

        /**
         * The constructor for this Tag; this should contain all properties for this object.
         *
         * @param Description $description Tag value
         *
         * @see BaseTag for the declaration of the description property and getDescription method.
         */
        public function __construct(Description $description = null) {
            $this->description = $description;
        }

        /**
         * Create the tag
         *
         * @param string             $body               Tag body
         * @param DescriptionFactory $descriptionFactory The description factory
         * @param Context|null       $context            The Context is used to resolve Types and FQSENs, although optional
         *                                               it is highly recommended to pass it. If you omit it then it is assumed that
         *                                               the DocBlock is in the global namespace and has no `use` statements.
         *
         * @return SleepTime
         */
        public static function create($body, DescriptionFactory $descriptionFactory = null, Context $context = null) {
            Assert::integerish($body, self::MSG);
            Assert::greaterThanEq($body, 0, self::MSG);
            Assert::notNull($descriptionFactory);

            return new static($descriptionFactory->create($body, $context));
        }

        /**
         * Returns a rendition of the original tag line.
         *
         * This method is used to reconstitute a DocBlock into its original form by the {@see Serializer}. It should
         * feature all parts of the tag so that the serializer can put it back together.
         *
         * @return string
         */
        public function __toString() {
            return (string)$this->description;
        }
    }