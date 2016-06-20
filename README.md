Allows failed [PHPUnit](https://phpunit.de/) tests to automatically rerun.

# Use cases
Anywhere where there is an external dependency which you don't have full control of, e.g. when developing an SDK for some API, which might apply rate limiting or experience brief downtime.

# Requirements

- Tested on HHVM
- PHP 5.6 or 7.0+
- Tested on PHPUnit 5.4 (`require-dev ^5.0`), but might run on older versions (update your installation!)

# Installation
```sh
composer require --dev alorel/phpunit-auto-rerun
```

# Usage
It is simply a class extending the original `PHPUnit_Framework_TestCase`, therefore all you need to do is replace the class in your test cases:

```php
<?php

    namespace Some\Thing\Or\Another;

    class PHPUnitReflectionTest extends \PHPUnit_Framework_TestCase {
```
would become
```php
<?php

    namespace Some\Thing\Or\Another;

    class PHPUnitReflectionTest extends \PHPUnit_Retriable_TestCase {
```

# Modifying existing test cases/reverting back
The `PHPUnit_Retriable_TestCase` class was intentionally left in the global namespace to resemble the original `PHPUnit_Framework_TestCase` as much as possible.
Most IDEs will generate/suggest a test case template to contain
```php
class PHPUnitReflectionTest extends \PHPUnit_Retriable_TestCase
```
so you can safely do a tests-wide search-and-replace operation in either direction.

# Configuration
Configuration is performed via annotations, same way you'd configure expected exceptions, `@before`s and so on:

- `@retryCount` specifies the maximum number of times a test case will run. Setting this to `0` will simply invoke the original test runner, setting it to `1` will run a test without retries (equivalent functionality), setting it to `5` will run it once and retry up to 4 times upon failure.
- `@sleepTime` specifies how many seconds the script will [sleep](https://secure.php.net/manual/en/function.sleep.php) between retries.

Both these values default to `0`.

Configuration can be performed by annotating both the class and the test method - class annotations are applied to all methods and are overriden by method annotations, for example, consider the following snippet:

```php
<?php

    /**
     * @retryCount 5
     */
    class SomeTest extends PHPUnit_Retriable_TestCase {

        function testOne() {
            //Will inherit the default sleep time of 0
            //and SomeTest's retry count of 5
        }

        /**
         * @retryCount 0
         */
        function testTwo() {
            //sleep time 0 (default)
            //retry count 0 (overrides SomeTest)
        }

        /**
         * @sleepTime 10
         */
        function testThree() {
            //sleep time 10 (overrides default)
            //retry count 5 (inherit from SomeTest)
        }

        /**
         * @sleepTime 3
         * @retryCount 3
         */
        function testFour() {
            //sleep time 3 (overrides default)
            //retry count 3 (overrides SomeTest)
        }
    }
```

# FAQ `*`

> **Q:** Will this work with `@dataProvider` annotations?
> **A:** Yes - the test will continue retrying with the same data provider value and continue to the next one when the test succeeds.
> This applies to both array data providers and generators/iterators.

----------

> **Q:** Why do I end up with a higher number of assertions than before?
> **A:** Even when a test fails it still increments the number of assertions made - it was not immediately obvious how to change this
> and I don't see it as an important feature, therefore I didn't bother
> implementing it.

----------

> **Q:** Can I set the configuration parameters via CLI/phpunit.xml?
> **A:** No and it does not appear to be possible without editing the original PHPUnit code.

----------


`*` I wrote put these together before release, no one's asked them. Apologies for the ruse. :disappointed: 