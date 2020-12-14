<?php

namespace tests;

use app\models;
use app\interfaces;

/**
 * UserTest contains test cases for user model
 * 
 * IMPORTANT NOTE:
 * All test cases down below must be implemented
 * You can add new test cases on your own
 * If they could be helpful in any form
 */
class UserTest extends \Codeception\Test\Unit
{
    /**
     * Test case for adding repo models to user model
     *
     * IMPORTANT NOTE:
     * Should cover succeeded and failed suites
     *
     * @return void
     */
    public function testAddingRepos()
    {
        $stubRepo1 = $this->createMock(interfaces\IRepo::class);
        $stubRepo1->method("getRating")->willReturn(100500.9);
        $reposForAdding = array($stubRepo1);

        $user = new models\User("1", "TestUser", "TestPlatform");
        $user->addRepos($reposForAdding);
        $actualUserRepositories = $this->getPrivatePropertyValue($user, "repositories");

        $expectedRepoRatings = array(100500.9);

        $this->validateUserRepositories($reposForAdding, $expectedRepoRatings, $actualUserRepositories);
    }

    /**
     * Test case for adding two repo models to user model
     *
     * @return void
     */
    public function testAddingTwoRepos()
    {
        $stubRepo1 = $this->createMock(interfaces\IRepo::class);
        $stubRepo1->method("getRating")->willReturn(1.1);
        $stubRepo2 = $this->createMock(interfaces\IRepo::class);
        $stubRepo2->method("getRating")->willReturn(100.0);
        $reposForAdding = array($stubRepo1, $stubRepo2);

        $user = new models\User("1", "TestUser", "TestPlatform");
        $user->addRepos($reposForAdding);

        $actualUserRepositories = $this->getPrivatePropertyValue($user, "repositories");

        $expectedRepoRatings = array(100.0, 1.1);

        $this->validateUserRepositories($reposForAdding, $expectedRepoRatings, $actualUserRepositories);
    }

    /**
     * Test case for adding three repo models to user model
     *
     * @return void
     */
    public function testAddingThreeRepos()
    {
        $stubRepo1 = $this->createMock(interfaces\IRepo::class);
        $stubRepo1->method("getRating")->willReturn(0.0);
        $stubRepo2 = $this->createMock(interfaces\IRepo::class);
        $stubRepo2->method("getRating")->willReturn(-1.0);
        $stubRepo3 = $this->createMock(interfaces\IRepo::class);
        $stubRepo3->method("getRating")->willReturn(99.9);
        $reposForAdding = array($stubRepo1, $stubRepo2, $stubRepo3);

        $user = new models\User("1", "TestUser", "TestPlatform");
        $user->addRepos($reposForAdding);
        $actualUserRepositories = $this->getPrivatePropertyValue($user, "repositories");

        $expectedRepoRatings = array(99.9, 0.0, -1.0);

        $this->validateUserRepositories($reposForAdding, $expectedRepoRatings, $actualUserRepositories);
    }

    /**
     * Test case for adding another repo to existing one in user model
     *
     * @return void
     */
    public function testAddingAnotherRepoToExistingOne()
    {
        $stubRepo1 = $this->createMock(interfaces\IRepo::class);
        $stubRepo1->method("getRating")->willReturn(11.0);
        $stubRepo2 = $this->createMock(interfaces\IRepo::class);
        $stubRepo2->method("getRating")->willReturn(-1.0);

        $user = new models\User("1", "TestUser", "TestPlatform");
        $user->addRepos(array($stubRepo1));
        $user->addRepos(array($stubRepo2));

        $addedRepos = array($stubRepo1,$stubRepo2);

        $actualUserRepositories = $this->getPrivatePropertyValue($user, "repositories");
        $expectedRepoRatings = array(11.0, -1.0);

        $this->validateUserRepositories($addedRepos, $expectedRepoRatings, $actualUserRepositories);
    }

    /**
     * Test case for adding empty repos models array to user model
     *
     * @return void
     */
    public function testAddingReposWithEmptyReposArray()
    {
        $reposForAdding = array();

        $user = new models\User("", "TestUser", "TestPlatform");
        $user->addRepos($reposForAdding);
        $actualUserRepositories = $this->getPrivatePropertyValue($user, "repositories");

        $this->assertTrue(0 == count($actualUserRepositories));
    }

    /**
     * Test case for adding only one repo model with wrong Interface to user model
     *
     * @return void
     *
     * IMPORTANT! This test falls as expected.
     * This test falls due to a bug in the core product code.
     * There is no interface check in the code when adding only one repository.
     *
     * Actual result: Repo with the wrong interface is successfully added to list to the user. No exceptions are thrown.
     * Expected result: Application throw LogicException.
     *
     * TicketID: @{Here can be your TicketID in Jira}
     */
    public function testAddingOnlyOneRepoWithWrongInterface()
    {
        $stubRepo1 = $this->createMock(interfaces\IPlatform::class);
        $user = new models\User(0.1, "TestUser", "TestPlatform");

        $this->expectException(\LogicException::class);

        $user->addRepos(array($stubRepo1));
    }

    /**
     * Test case for adding two repos models to user model and first repo has wrong Interface
     *
     * @return void
     */
    public function testAddingTwoReposAndFirstRepoHasWrongInterface()
    {
        $stubRepo1 = $this->createMock(interfaces\IPlatform::class);
        $stubRepo2 = $this->createMock(interfaces\IRepo::class);
        $user = new models\User("1", "TestUser", "TestPlatform");

        $this->expectException(\LogicException::class);

        $user->addRepos(array($stubRepo1, $stubRepo2));
    }

    /**
     * Test case for adding two repos models to user model and second repo has wrong Interface
     *
     * @return void
     */
    public function testAddingTwoReposAndSecondRepoHasWrongInterface()
    {
        $stubRepo1 = $this->createMock(interfaces\IRepo::class);
        $stubRepo2 = $this->createMock(interfaces\IPlatform::class);
        $user = new models\User("1", "TestUser", "TestPlatform");

        $this->expectException(\LogicException::class);

        $user->addRepos(array($stubRepo1, $stubRepo2));
    }

    /**
     * Test case for counting total user rating with null instead repos array
     *
     * @return void
     */
    public function testAddingReposWithNullRepos()
    {
        $reposForAdding = null;
        $user = new models\User(1, "TestUser", "TestPlatform");

        $this->expectException(\TypeError::class);

        $user->addRepos($reposForAdding);
    }

    /**
     * Test case for counting total user rating
     *
     * @return void
     */
    public function testTotalRatingCount()
    {
        $stubRepo1 = $this->createMock(interfaces\IRepo::class);
        $stubRepo1->method("getRating")->willReturn(11.0);
        $reposForAdding = array($stubRepo1);

        $user = new models\User(1, "TestUser", "TestPlatform");
        $user->addRepos($reposForAdding);
        $actualTotalRating = $user->getTotalRating();

        $this->assertEquals(11.0, $actualTotalRating);
    }

    /**
     * Test case for counting total user rating with two repos
     *
     * @return void
     */
    public function testTotalRatingCountWithTwoRepos()
    {
        $stubRepo1 = $this->createMock(interfaces\IRepo::class);
        $stubRepo1->method("getRating")->willReturn(0.0);
        $stubRepo2 = $this->createMock(interfaces\IRepo::class);
        $stubRepo2->method("getRating")->willReturn(-1.0);
        $reposForAdding = array($stubRepo1, $stubRepo2);

        $user = new models\User("1", "TestUser", "TestPlatform");
        $user->addRepos($reposForAdding);
        $actualTotalRating = $user->getTotalRating();

        $this->assertEquals(-1.0, $actualTotalRating);
    }

    /**
     * Test case for user model data serialization
     *
     * @return void
     *
     * NOTE: a small error in the core product code is possible.
     * The returned model contains 2 arrays of repositories 'repos' and 'repo'.
     * And one of them is empty (repos).
     */
    public function testData()
    {
        $repo1 = new models\GithubRepo('TestRepo', 10, 10, 10);
        $reposForAdding = array($repo1);
        $user = new models\User("1", "TestUser", "Github");
        $user->addRepos($reposForAdding);
        $actualData = $user->getData();

        $expectedData = array(
            'name' => "TestUser",
            'platform' => "Github",
            'total-rating' => 11.666666666667,
            'repos' => [],
            'repo' => array(
                array(
                'name' => "TestRepo",
                'fork-count' => 10,
                'start-count' => 10,
                'watcher-count' => 10,
                'rating' => 11.666666666667
                )
            )
        );

        $this->assertEquals($expectedData, $actualData);
    }

    /**
     * Test case for user model data serialization with two repos
     *
     * @return void
     */
    public function testDataWithTwoRepos()
    {
        $repo2 = new models\GithubRepo('NewTestRepo', -1, 14, 0);
        $repo1 = new models\GithubRepo('TestRepo', -65.2, 10, 16.4);
        $reposForAdding = array($repo1, $repo2);
        $user = new models\User("1", "TestUser", "Github");
        $user->addRepos($reposForAdding);
        $actualData = $user->getData();

        $expectedData = array(
            'name' => "TestUser",
            'platform' => "Github",
            'total-rating' => -34.666666666667,
            'repos' => [],
            'repo' => array(
                array(
                    'name' => "NewTestRepo",
                    'fork-count' => -1,
                    'start-count' => 14,
                    'watcher-count' => 0,
                    'rating' => 1.6666666666667
                ),
                array(
                    'name' => "TestRepo",
                    'fork-count' => -65.2,
                    'start-count' => 10,
                    'watcher-count' => 16.4,
                    'rating' => -36.333333333333
                )
            )
        );

        $this->assertEquals($expectedData, $actualData);
    }

    /**
     * Test case for user model data serialization without repos
     *
     * @return void
     */
    public function testDataWithoutRepos()
    {
        $user = new models\User("1", "TestUser", "Github");
        $actualData = $user->getData();

        $expectedData = array(
            'name' => "TestUser",
            'platform' => "Github",
            'total-rating' => 0.0,
            'repos' => []
        );

        $this->assertEquals($expectedData, $actualData);
    }

    /**
     * Test case for user model __toString verification
     *
     * @return void
     */
    public function testStringify()
    {
        $repo1 = new models\GithubRepo('NewTestRepo', -1, 14, 0);
        $reposForAdding = array($repo1);
        $user = new models\User("1", "TestUser", "Github");
        $user->addRepos($reposForAdding);
        $result = $user->__toString();

        $expected = "TestUser (Github)                                                                             1 🏆\n==================================================================================================\nNewTestRepo                                                                   -1 ⇅   14 ★    0 👁️\n";

        $this->assertEquals($expected, $result);
    }

    /**
     * Test case for user model __toString verification with two repos
     *
     * @return void
     */
    public function testStringifyWithTwoRepos()
    {
        $repo2 = new models\GithubRepo('NewTestRepo', -1, 14, 0);
        $repo1 = new models\GithubRepo('TestRepo', -65.2, 10, 16.4);
        $reposForAdding = array($repo1, $repo2);
        $user = new models\User(-1, "TestUser", "Github");
        $user->addRepos($reposForAdding);
        $result = $user->__toString();

        $expected = "TestUser (Github)                                                                           -34 🏆\n==================================================================================================\nNewTestRepo                                                                   -1 ⇅   14 ★    0 👁️\nTestRepo                                                                     -65 ⇅   10 ★   16 👁️\n";

        $this->assertEquals($expected, $result);
    }

    /**
     * Test case for user model __toString verification without repos
     *
     * @return void
     */
    public function testStringifyWithoutRepos()
    {
        $user = new models\User(1, "TestUser", "Github");
        $result = $user->__toString();

        $expected = "TestUser (Github)                                                                             0 🏆\n==================================================================================================\n";

        $this->assertEquals($expected, $result);
    }

    /**
     * Test case for getting Identifier with negative number in string format
     *
     * @return void
     */
    public function testGetIdentifier()
    {
        $user = new models\User("-1", "TestUser", "Github");
        $result = $user->getIdentifier();

        $expected = -1;

        $this->assertEquals($expected, $result);
    }

    /**
     * Test case for getting Identifier with empty string
     *
     * @return void
     */
    public function testGetIdentifierWithEmptyString()
    {
        $user = new models\User("", "TestUser", "Github");
        $result = $user->getIdentifier();

        $expected = "";

        $this->assertEquals($expected, $result);
    }

    /**
     * Test case for getting user full-name
     *
     * @return void
     */
    public function testGetFullName()
    {
        $user = new models\User("0", "TestUser", "Github");
        $result = $user->getFullName();

        $expected = "TestUser (Github)";

        $this->assertEquals($expected, $result);
    }

    /**
     * Test case for getting user name
     *
     * @return void
     */
    public function testGetName()
    {
        $user = new models\User("0", "TestUser", "Github");
        $result = $user->getName();

        $expected = "TestUser";

        $this->assertEquals($expected, $result);
    }

    /**
     * Test case for getting user platform
     *
     * @return void
     */
    public function testGetPlatform()
    {
        $user = new models\User("0", "TestUser", "Github");
        $result = $user->getPlatform();

        $expected = "Github";

        $this->assertEquals($expected, $result);
    }

    /**
     * Validator user repositories
     * - Compare expected repositories count
     * - Checking rating for each repository
     *
     * @param $addedRepos - added repos array
     * @param $expectedRepoRatings - expected repo ratings array
     * @param $actualUserRepositories - expected repo ratings array
     */
    private function validateUserRepositories($addedRepos, $expectedRepoRatings, $actualUserRepositories)
    {
        $this->assertTrue(count($addedRepos) == count($actualUserRepositories));

        for ($i = 0; $i < count($actualUserRepositories); $i++) {
            $actualRating = $actualUserRepositories[$i]->getRating();
            $expectedRating = $expectedRepoRatings[$i];

            $this->assertEquals($expectedRating, $actualRating);
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
        $value = $reflection_property->getValue($object);

        return $value;
    }
}