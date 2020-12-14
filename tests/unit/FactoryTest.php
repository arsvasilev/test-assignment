<?php

namespace tests;

use app\components;

/**
 * FactoryTest contains test cases for factory component
 * 
 * IMPORTANT NOTE:
 * All test cases down below must be implemented
 * You can add new test cases on your own
 * If they could be helpful in any form
 */
class FactoryTest extends \Codeception\Test\Unit
{
    /**
     * Test case for creating platform component
     *
     * IMPORTANT NOTE:
     * Should cover succeeded and failed suites
     */

    // Test data provider for positive testing creation platform component
    public function positiveDataProvider(): array
    {
        // Variables: $platformName, $expectedObjectClass
        return array(
            array("github", "app\components\platforms\Github"),
            array("gitlab", "app\components\platforms\Gitlab"),
            array("bitbucket", "app\components\platforms\Bitbucket")
        );
    }

    /**
     *  Test case for creating platform component with available platforms (positive cases)
     *
     * @dataProvider positiveDataProvider
     * @param $platformName - platform name
     * @param $expectedObjectClass - expected objects class
     * @return void
     */
    public function testCreate($platformName, $expectedObjectClass)
    {
        $factory = new components\Factory();
        $platform = $factory->create($platformName);

        $this->assertInstanceOf($expectedObjectClass, $platform);
    }

    // Test data provider for negative testing creation platform component
    public function negativeDataProvider(): array
    {
        // Variables: $platformName, $expectedException
        return array(
            array("GITLAB", \LogicException::class),
            array("unknown", \LogicException::class),
            array("", \LogicException::class),
            array(null, \TypeError::class)
        );
    }

    /**
     * Cases for testing failed scenarios during creating platform component
     *
     * @dataProvider negativeDataProvider
     * @param $platformName - platform name
     * @param $expectedException - expected exception
     * @return void
     */
    public function testCreateNegative($platformName, $expectedException)
    {
        $this->expectException($expectedException);

        $factory = new components\Factory();
        $factory->create($platformName);
    }

    /**
     * Test case for creating platform component with using one cached platform
     *
     * @return void
     */
    public function testCreateWithOneCachedPlatform()
    {
        $factory = new components\Factory();
        $factory->create('gitlab');
        $cachedPlatforms = $this->getPrivatePropertyValue($factory, "cahce");

        $expectedObjectClasses = array("app\components\platforms\Gitlab");

        $this->validateCachedPlatforms($cachedPlatforms, $expectedObjectClasses);

        $createdPlatformFromCache = $factory->create('gitlab');
        $expectedObjectClass = 'app\components\platforms\Gitlab';

        $this->assertInstanceOf($expectedObjectClass, $createdPlatformFromCache);
    }

    /**
     * Test case for creating platform component with using two cached platforms
     *
     * @return void
     */
    public function testCreateWithTwoCachedPlatforms()
    {
        $factory = new components\Factory();
        $factory->create('gitlab');
        $factory->create('github');
        $cachedPlatforms = $this->getPrivatePropertyValue($factory, "cahce");

        $expectedObjectClasses = array("app\components\platforms\Gitlab", "app\components\platforms\Github");

        $this->validateCachedPlatforms($cachedPlatforms, $expectedObjectClasses);

        $createdPlatformFromCache = $factory->create('github');
        $expectedObjectClass = 'app\components\platforms\Github';

        $this->assertInstanceOf($expectedObjectClass, $createdPlatformFromCache);
    }

    /**
     * Test case for creating platform component with using three cached platforms
     *
     * @return void
     */
    public function testCreateWithThreeCachedPlatforms()
    {
        $factory = new components\Factory();
        $factory->create('gitlab');
        $factory->create('github');
        $factory->create('bitbucket');
        $cachedPlatforms = $this->getPrivatePropertyValue($factory, "cahce");

        $expectedObjectClasses = array("app\components\platforms\Gitlab",
                                       "app\components\platforms\Github",
                                       "app\components\platforms\Bitbucket");

        $this->validateCachedPlatforms($cachedPlatforms, $expectedObjectClasses);

        $createdPlatformFromCache = $factory->create('gitlab');
        $expectedObjectClass = 'app\components\platforms\Gitlab';

        $this->assertInstanceOf($expectedObjectClass, $createdPlatformFromCache);
    }

    /**
     * Validator Cached platforms
     * - Compare expected platforms count
     * - Checking ObjectClasses types for each cached-platform
     *
     * @param $cachedPlatforms - cached platform array
     * @param $expectedObjectClasses - expected objects classes array
     */
    private function validateCachedPlatforms($cachedPlatforms, $expectedObjectClasses)
    {
        $this->assertTrue(count($expectedObjectClasses) == count($cachedPlatforms));

        $iterator = 0;
        foreach ($cachedPlatforms as $cachedPlatform) {
            $this->assertInstanceOf($expectedObjectClasses[$iterator], $cachedPlatform);
            $iterator++;
        }
    }

    /**
     * Helper method for reflect testing object and getting value from private property
     *
     * NOTE: For best practice, it would be nice to move this method into a separate file.
     * But by the terms of the test-task, I cannot modify any other application files.
     *
     * @param $object - object for reflection
     * @param $property - objects property name for getting value
     * @return mixed
     */
    private function getPrivatePropertyValue($object, $property)
    {
        $reflection = new \ReflectionClass($object);
        $reflection_property = $reflection->getProperty($property);
        $reflection_property->setAccessible(true);

        return $reflection_property->getValue($object);
    }
}