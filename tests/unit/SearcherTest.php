<?php

namespace tests;

use app\models;
use app\interfaces;
use app\components;

/**
 * SearcherTest contains test cases for searcher component
 * 
 * IMPORTANT NOTE:
 * All test cases down below must be implemented
 * You can add new test cases on your own
 * If they could be helpful in any form
 */
class SearcherTest extends \Codeception\Test\Unit
{
    /**
     * Test case for searching via several platforms
     *
     * IMPORTANT NOTE:
     * Should cover succeeded and failed suites
     */

    /**
     * Test case for searching via one platform
     *
     * @return void
     */
    public function testSearcherViaOnePlatform()
    {
        $user = new models\User("1", "TestUser", "TestPlatform");
        $userRepos = array(new models\GithubRepo("foo", 1, 2, 3));

        $stubPlatform = $this->createMock(interfaces\IPlatform::class);
        $stubPlatform->method('findUserRepos')->willReturn($userRepos);
        $stubPlatform->method('findUserInfo')->willReturn($user);
        $platforms = array($stubPlatform);

        $userNames = array("TestUser");
        $searcher = new components\Searcher();
        $result = $searcher->search($platforms, $userNames);

        $expectedUser = new models\User("1", "TestUser", "TestPlatform");
        $expectedUser->addRepos($userRepos);
        $expected = array($expectedUser);

        $this->assertEquals($expected, $result);
    }

    /**
     * Test case for searching via two platforms
     *
     * @return void
     */
    public function testSearcherViaTwoPlatforms()
    {
        $user1 = new models\User("1", "TestUser", "Github");
        $user2 = new models\User("2", "TestUser2", "Gitlab");

        $userRepos1 = array(new models\GithubRepo("foo", 1, 2, 3));
        $userRepos2 = array(new models\GitlabRepo("testRepo", 10, 22));

        $stubPlatform1 = $this->createMock(interfaces\IPlatform::class);
        $stubPlatform1->method('findUserRepos')->will($this->onConsecutiveCalls($userRepos1));
        $stubPlatform1->method('findUserInfo')->will($this->onConsecutiveCalls($user1, null));
        $stubPlatform2 = $this->createMock(interfaces\IPlatform::class);
        $stubPlatform2->method('findUserRepos')->will($this->onConsecutiveCalls($userRepos2));
        $stubPlatform2->method('findUserInfo')->will($this->onConsecutiveCalls($user2, null));
        $platforms = array($stubPlatform1, $stubPlatform2);

        $userNames = array("TestUser", "TestUser2");
        $searcher = new components\Searcher();
        $result = $searcher->search($platforms, $userNames);

        $expectedUser1 = new models\User("1", "TestUser", "Github");
        $expectedUser1->addRepos($userRepos1);
        $expectedUser2 = new models\User("2", "TestUser2", "Gitlab");
        $expectedUser2->addRepos($userRepos2);
        $expected = array($expectedUser2, $expectedUser1);

        $this->assertEquals($expected, $result);
    }

    /**
     * Test case for searching via three platforms
     *
     * @return void
     */
    public function testSearcherViaThreePlatforms()
    {
        $user1 = new models\User("1", "TestUser", "Github");
        $user2 = new models\User("2", "TestUser2", "Gitlab");
        $user3 = new models\User("3", "TestUser3", "Bitbucket");

        $userRepos1 = array(new models\GithubRepo("foo", 1, 2, 3));
        $userRepos2 = array(new models\GitlabRepo("testRepo", 10, 0));
        $userRepos3 = array(new models\BitbucketRepo("testRepo", 88, -1));

        $stubPlatform1 = $this->createMock(interfaces\IPlatform::class);
        $stubPlatform1->method('findUserRepos')->will($this->onConsecutiveCalls($userRepos1));
        $stubPlatform1->method('findUserInfo')->will($this->onConsecutiveCalls($user1, null));
        $stubPlatform2 = $this->createMock(interfaces\IPlatform::class);
        $stubPlatform2->method('findUserRepos')->will($this->onConsecutiveCalls($userRepos2));
        $stubPlatform2->method('findUserInfo')->will($this->onConsecutiveCalls($user2, null));
        $stubPlatform3 = $this->createMock(interfaces\IPlatform::class);
        $stubPlatform3->method('findUserRepos')->will($this->onConsecutiveCalls($userRepos3));
        $stubPlatform3->method('findUserInfo')->will($this->onConsecutiveCalls($user3, null));
        $platforms = array($stubPlatform1, $stubPlatform2, $stubPlatform3);

        $userNames = array("TestUser", "TestUser2");
        $searcher = new components\Searcher();
        $result = $searcher->search($platforms, $userNames);

        $expectedUser1 = new models\User("1", "TestUser", "Github");
        $expectedUser1->addRepos($userRepos1);
        $expectedUser2 = new models\User("2", "TestUser2", "Gitlab");
        $expectedUser2->addRepos($userRepos2);
        $expectedUser3 = new models\User("3", "TestUser3", "Bitbucket");
        $expectedUser3->addRepos($userRepos3);
        $expected = array($expectedUser3, $expectedUser2, $expectedUser1);

        $this->assertEquals($expected, $result);
    }

    /**
     * Test case for searching via one platform with two users
     *
     * @return void
     */
    public function testSearcherViaOnePlatformWithTwoUsers()
    {
        $user1 = new models\User("1", "TestUser", "TestPlatform");
        $user2 = new models\User("2", "SecondTestUser", "TestPlatform");

        $userRepos1 = array(new models\GithubRepo("foo", 1, 2, 3));
        $userRepos2 = array(new models\GitlabRepo("testRepo", 10, 22));

        $stubPlatform = $this->createMock(interfaces\IPlatform::class);
        $stubPlatform->method('findUserRepos')->will($this->onConsecutiveCalls($userRepos1, $userRepos2));
        $stubPlatform->method('findUserInfo')->will($this->onConsecutiveCalls($user1, $user2));
        $platforms = array($stubPlatform);

        $userNames = array("TestUser", "SecondTestUser");
        $searcher = new components\Searcher();
        $result = $searcher->search($platforms, $userNames);

        $expectedUser1 = new models\User("1", "TestUser", "TestPlatform");
        $expectedUser1->addRepos($userRepos1);
        $expectedUser2 = new models\User("2", "SecondTestUser", "TestPlatform");
        $expectedUser2->addRepos($userRepos2);
        $expected = array($expectedUser2, $expectedUser1);

        $this->assertEquals($expected, $result);
    }

    /**
     * Test case for searching with empty platforms array
     *
     * @return void
     */
    public function testSearcherWithEmptyPlatforms()
    {
        $platforms = array();

        $userNames = array("TestUser");
        $searcher = new components\Searcher();
        $result = $searcher->search($platforms, $userNames);

        $expected = array();

        $this->assertEquals($expected, $result);
    }

    /**
     * Test case for searching with empty user names array
     *
     * @return void
     */
    public function testSearcherWithEmptyUserNames()
    {
        $userNames = array();

        $user = new models\User("1", "TestUser", "TestPlatform");
        $userRepos = array(new models\GithubRepo("foo", 1, 2, 3));

        $stubPlatform = $this->createMock(interfaces\IPlatform::class);
        $stubPlatform->method('findUserRepos')->willReturn($userRepos);
        $stubPlatform->method('findUserInfo')->willReturn($user);
        $platforms = array($stubPlatform);

        $searcher = new components\Searcher();
        $result = $searcher->search($platforms, $userNames);

        $expected = array();

        $this->assertEquals($expected, $result);
    }

    /**
     * Test case for search using user without repositories
     *
     * @return void
     */
    public function testSearcherWithUserWithoutRepos()
    {
        $user = new models\User("1", "TestUser", "TestPlatform");
        $userRepos = array();

        $stubPlatform = $this->createMock(interfaces\IPlatform::class);
        $stubPlatform->method('findUserRepos')->willReturn($userRepos);
        $stubPlatform->method('findUserInfo')->willReturn($user);
        $platforms = array($stubPlatform);

        $userNames = array("TestUser");
        $searcher = new components\Searcher();
        $result = $searcher->search($platforms, $userNames);

        $expected = array();

        $this->assertEquals($expected, $result);
    }

    /**
     * Test case for search with null instead user
     *
     * @return void
     */
    public function testSearcherWithoutUser()
    {
        $user = null;
        $userRepos = array(new models\GithubRepo("foo", 1, 2, 3));

        $stubPlatform = $this->createMock(interfaces\IPlatform::class);
        $stubPlatform->method('findUserRepos')->willReturn($userRepos);
        $stubPlatform->method('findUserInfo')->willReturn($user);
        $platforms = array($stubPlatform);

        $userNames = array("TestUser");
        $searcher = new components\Searcher();
        $result = $searcher->search($platforms, $userNames);

        $expected = array();

        $this->assertEquals($expected, $result);
    }

    /**
     * Test case for search with null instead platforms
     *
     * @return void
     */
    public function testSearcherWithNullPlatforms()
    {
        $this->expectException(\TypeError::class);

        $userNames = array("TestUser");
        $searcher = new components\Searcher();
        $searcher->search(null, $userNames);
    }

    /**
     * Test case for search with empty string instead platforms
     *
     * @return void
     */
    public function testSearcherWithStringEmptyInsteadPlatforms()
    {
        $this->expectException(\TypeError::class);

        $userNames = array("TestUser");
        $searcher = new components\Searcher();
        $searcher->search(null, $userNames);
    }

    /**
     * Test case for search with null instead userNames
     *
     * @return void
     */
    public function testSearcherWithNullUserNames()
    {
        $this->expectException(\TypeError::class);

        $user = new models\User("1", "TestUser", "TestPlatform");;
        $userRepos = array(new models\GithubRepo("foo", 1, 2, 3));

        $stubPlatform = $this->createMock(interfaces\IPlatform::class);
        $stubPlatform->method('findUserRepos')->willReturn($userRepos);
        $stubPlatform->method('findUserInfo')->willReturn($user);
        $platforms = array($stubPlatform);

        $searcher = new components\Searcher();
        $searcher->search($platforms, null);
    }

    /**
     * Test case for search with empty string instead userNames
     *
     * @return void
     */
    public function testSearcherWithStringEmptyInsteadUserNames()
    {
        $this->expectException(\TypeError::class);

        $user = new models\User("1", "TestUser", "TestPlatform");;
        $userRepos = array(new models\GithubRepo("foo", 1, 2, 3));

        $stubPlatform = $this->createMock(interfaces\IPlatform::class);
        $stubPlatform->method('findUserRepos')->willReturn($userRepos);
        $stubPlatform->method('findUserInfo')->willReturn($user);
        $platforms = array($stubPlatform);

        $searcher = new components\Searcher();
        $searcher->search($platforms, "");
    }
}