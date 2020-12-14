<?php

namespace tests;

use app\models;

/**
 * GithubRepoTest contains test cases for github repo model
 * 
 * IMPORTANT NOTE:
 * All test cases down below must be implemented
 * You can add new test cases on your own
 * If they could be helpful in any form
 */
class GithubRepoTest extends \Codeception\Test\Unit
{
    // Test data provider for testing 'getRating' method
    public function ratingCountDataProvider() : array
    {
        // Variables: $name, $forkCount, $starsCount, $watcherCount, $expectedRating
        return array(
            array("TestRepo", 5, 10, 11, 8.6666666666667),
            array("TestRepo", 0 , 0, 0, 0.0),
            array("TestRepo", 0.1 , 0.2, 0.3, 0.20000000000000004),
            array("TestRepo", -1 , -15, -60, -23.166666666667),
            array("TestRepo", null ,null, null, 0.0),
        );
    }

    /**
     * Test case for counting repo rating
     *
     * @dataProvider ratingCountDataProvider
     * @param $name - repository name
     * @param $forkCount - repository fork count
     * @param $starsCount  - repository stars count
     * @param $watcherCount - repository watcher count
     * @param $expectedRating - expected rating count
     * @return void
     */
    public function testRatingCount($name, $forkCount, $starsCount, $watcherCount, $expectedRating)
    {
        $repo = new models\GithubRepo($name, $forkCount, $starsCount ,$watcherCount);

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

        $repo = new models\GithubRepo("TestRepo", "one", "two", "five");
        $repo->getRating();
    }

    // Test data provider for testing 'getData' method
    public function getDataDataProvider() : array
    {
        // Variables: $name, $forkCount, $starsCount, $watcherCount, $expectedData
        return array(
            array(
                "NewTestRepo",
                10,
                10,
                10,
                array(
                    'name' => "NewTestRepo",
                    'fork-count' => 10,
                    'start-count' => 10,
                    'watcher-count' => 10,
                    'rating' => 11.666666666667
                )
            ),
            array(
                null,
                null,
                null,
                null,
                array(
                    'name' => null,
                    'fork-count' => null,
                    'start-count' => null,
                    'watcher-count' => null,
                    'rating' => 0.0
                )
            ),
            array(
                1,
                2,
                3,
                4,
                array(
                    'name' => 1,
                    'fork-count' => 2,
                    'start-count' => 3,
                    'watcher-count' => 4,
                    'rating' => 3.1666666666666665
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
     * @param $starsCount  - repository stars count
     * @param $watcherCount - repository watcher count
     * @param $expectedData - expected repository data
     * @return void
     */
    public function testGetData($name, $forkCount, $starsCount, $watcherCount, $expectedData)
    {
        $repo = new models\GithubRepo($name, $forkCount, $starsCount ,$watcherCount);

        $result = $repo->getData();

        $this->assertEquals($expectedData, $result);
    }

    /**
     * Test case for catching Exception via data serialization with string empty values
     *
     * @return void
     */
    public function testGetDataWithStringEmptyValues()
    {
        $this->expectException(\ErrorException::class);

        $repo = new models\GithubRepo("", "", "", "");
        $repo->getData();
    }

    // Test data provider for testing '__toString' method
    public function stringifyDataProvider() : array
    {
        // Variables: $name, $forkCount, $starsCount, $watcherCount, $expectedString
        return array(
            array("TestName", 999 , 14, 1000, "TestName                                                                     999 ⇅   14 ★ 1000 👁️"),
            array(null, null, null, null, "                                                                               0 ⇅    0 ★    0 👁️"),
            array("", "" , "", "", "                                                                               0 ⇅    0 ★    0 👁️"),
        );
    }

    /**
     * Test case for repo model __toString verification
     *
     * @dataProvider stringifyDataProvider
     * @param $name - repository name
     * @param $forkCount - repository fork count
     * @param $starsCount  - repository stars count
     * @param $watcherCount - repository watcher count
     * @param $expectedString - expected formatted string
     * @return void
     */
    public function testStringify($name, $forkCount, $starsCount, $watcherCount, $expectedString)
    {
        $repo = new models\GithubRepo($name, $forkCount, $starsCount ,$watcherCount);

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
        $repo = new models\GithubRepo("TestName",1 ,2 , 3);

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
        $repo = new models\GithubRepo("TestName",1 ,2 , 3);

        $expected = 1;

        $this->assertEquals($expected, $repo->getForkCount());
    }

    /**
     * Test case for getting repo watcher count
     *
     * @return void
     */
    public function testGetWatcherCount()
    {
        $repo = new models\GithubRepo("TestName",1 ,2 , 3);

        $expected = 3;

        $this->assertEquals($expected, $repo->getWatcherCount());
    }

    /**
     * Test case for getting repo stars count
     *
     * @return void
     */
    public function testGetStarCount()
    {
        $repo = new models\GithubRepo("TestName",1 ,2 , 3);

        $expected = 2;

        $this->assertEquals($expected, $repo->getStarCount());
    }
}