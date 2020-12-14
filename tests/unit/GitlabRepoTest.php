<?php

namespace tests;

use app\models;

/**
 * GitlabRepoTest contains test cases for gitlab repo model
 * 
 * IMPORTANT NOTE:
 * All test cases down below must be implemented
 * You can add new test cases on your own
 * If they could be helpful in any form
 */
class GitlabRepoTest extends \Codeception\Test\Unit
{
    // Test data provider for testing 'getRating' method
    public function ratingCountDataProvider() : array
    {
        // Variables: $name, $forkCount, $watcherCount, $expectedRating
        return array(
            array("TestRepo", 5 , 10, 7.5),
            array("TestRepo", 0 , 0, 0.0),
            array("TestRepo", 0.1 , 0.2, 0.15),
            array("TestRepo", -1 , -15, -4.75),
            array("TestRepo", null ,null, 0.0),
        );
    }

    /**
     * Test case for counting repo rating
     * @dataProvider ratingCountDataProvider
     * @param $name - repository name
     * @param $forkCount - repository fork count
     * @param $watcherCount - repository watcher count
     * @param $expectedRating - expected rating count
     * @return void
     */
    public function testRatingCount($name, $forkCount, $watcherCount, $expectedRating)
    {
        $repo = new models\GitlabRepo($name, $forkCount, $watcherCount);

        $result = $repo->getRating();

        $this->assertEquals($expectedRating, $result);
    }

    /**
     * Test case for catching Exception via getting rating with strings values
     *
     * @return void
     */
    public function testGetRatingWithStringsValues()
    {
        $this->expectException(\ErrorException::class);

        $repo = new models\GitlabRepo("TestRepo", "one", "two");
        $repo->getRating();
    }

    // Test data provider for testing 'getData' method
    public function getDataDataProvider() : array
    {
        // Variables: $name, $forkCount, $watcherCount, $expectedData
        return array(
            array(
                "NewTestRepo",
                10,
                10,
                array(
                    'name' => "NewTestRepo",
                    'fork-count' => 10,
                    'start-count' => 10,
                    'rating' => 12.5
                )
            ),
            array(
                null,
                null,
                null,
                array(
                    'name' => null,
                    'fork-count' => null,
                    'start-count' => null,
                    'rating' => 0.0
                )
            ),
            array(
                1,
                2,
                3,
                array(
                    'name' => 1,
                    'fork-count' => 2,
                    'start-count' => 3,
                    'rating' => 2.75
                )
            ),
        );
    }

    /**
     * Test case for repo model data serialization
     *
     * @dataProvider getDataDataProvider
     * @param $name - repository name
     * @param $forkCount - repository fork count
     * @param $watcherCount - repository watcher count
     * @param $expectedData - expected repository data
     * @return void
     */
    public function testGetData($name, $forkCount, $watcherCount, $expectedData)
    {
        $repo = new models\GitlabRepo($name, $forkCount, $watcherCount);

        $result = $repo->getData();

        $this->assertEquals($expectedData, $result);
    }

    /**
     * Test case for catching Exception via data serialization with string empty values
     * @return void
     */
    public function testGetDataWithStringEmptyValues()
    {
        $this->expectException(\ErrorException::class);

        $repo = new models\GitlabRepo("", "", "");
        $repo->getData();
    }

    // Test data provider for testing '__toString' method
    public function stringifyDataProvider() : array
    {
        // Variables: $name, $forkCount, $watcherCount, $expectedData
        return array(
            array("TestName", 999 , 14, "TestName                                                                     999 ⇅   14 ★"),
            array(null, null, null, "                                                                               0 ⇅    0 ★"),
            array("", "" , "", "                                                                               0 ⇅    0 ★"),
        );
    }

    /**
     * Test case for repo model __toString verification
     *
     * @dataProvider stringifyDataProvider
     * @param $name - repository name
     * @param $forkCount - repository fork count
     * @param $watcherCount - repository watcher count
     * @param $expectedString - expected formatted string
     * @return void
     */
    public function testStringify($name, $forkCount, $watcherCount, $expectedString)
    {
        $repo = new models\GitlabRepo($name, $forkCount, $watcherCount);

        $result = $repo->__toString();

        $this->assertEquals($expectedString, $result);
    }

    /**
     * Test case for getting repo Name
     *
     * @return void
     */
    public function testGetName()
    {
        $repo = new models\GitlabRepo("TestName",1 ,2);

        $expected = "TestName";

        $this->assertEquals($expected, $repo->getName());
    }

    /**
     * Test case for getting repo forks count
     *
     * @return void
     */
    public function testGetForkCount()
    {
        $repo = new models\GitlabRepo("TestName",1 ,2);

        $expected = 1;

        $this->assertEquals($expected, $repo->getForkCount());
    }

    /**
     * Test case for getting repo stars count
     *
     * @return void
     */
    public function testGetStarCount()
    {
        $repo = new models\GitlabRepo("TestName",1 ,2);

        $expected = 2;

        $this->assertEquals($expected, $repo->getStarCount());
    }

    /**
     * Test case for getting repo watcher count
     *
     * @return void
     */
    public function testGetWatcherCount()
    {
        $repo = new models\GitlabRepo("TestName",1 ,2);

        $expected = 0;

        $this->assertEquals($expected, $repo->getWatcherCount());
    }
}