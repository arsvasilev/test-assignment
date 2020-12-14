<?php

/**
 * Base contains test cases for testing api endpoint
 * 
 * @see https://codeception.com/docs/modules/Yii2
 * 
 * IMPORTANT NOTE:
 * All test cases down below must be implemented
 * You can add new test cases on your own
 * If they could be helpful in any form
 */
class BaseCest
{
    /**
     * Example test case
     *
     * @return void
     */
    public function cestExample(\FunctionalTester $I)
    {
        $I->amOnPage([
            'base/api',
            'users' => [
                'kfr',
            ],
            'platforms' => [
                'github',
            ]
        ]);

        $expected = json_decode('[
            {
                "name": "kfr",
                "platform": "github",
                "total-rating": 1,
                "repos": [],
                "repo": [
                    {
                        "name": "cards",
                        "fork-count": 0,
                        "start-count": 0,
                        "watcher-count": 0,
                        "rating": 0
                    },
                    {
                        "name": "kf-cli",
                        "fork-count": 0,
                        "start-count": 1,
                        "watcher-count": 1,
                        "rating": 0.5
                    },
                    {
                        "name": "UdaciCards",
                        "fork-count": 0,
                        "start-count": 0,
                        "watcher-count": 0,
                        "rating": 0
                    },
                    {
                        "name": "unikgen",
                        "fork-count": 0,
                        "start-count": 1,
                        "watcher-count": 1,
                        "rating": 0.5
                    }
                ]
            }
        ]');

        $I->assertEquals($expected, json_decode($I->grabPageSource()));
    }

    /**
     * Test case for api with bad request params
     *
     * @return void
     */
    public function cestBadParams(\FunctionalTester $I)
    {
        $I->amOnPage([
            'base/api',
            'bad_users' => [
                'kfr',
            ],
            'bad_platforms' => [
                'github',
            ]
        ]);

        $expectedResponseCode = 400;
        $expectedErrorMessage = 'Missing required parameters: users, platforms';

        $I->seeResponseCodeIs($expectedResponseCode);
        $I->see($expectedErrorMessage);
    }

    /**
     * Test case for api with empty user list
     *
     * @return void
     */
    public function cestEmptyUsers(\FunctionalTester $I)
    {
        $I->amOnPage([
            'base/api',
            'users' => [],
            'platforms' => [
                'gitlab',
            ]
        ]);

        $expectedResponseCode = 400;
        $expectedErrorMessage = 'Missing required parameters: users';

        $I->seeResponseCodeIs($expectedResponseCode);
        $I->see($expectedErrorMessage);
    }

    /**
     * Test case for api with empty platform list
     *
     * @return void
     */
    public function cestEmptyPlatforms(\FunctionalTester $I)
    {
        $I->amOnPage([
            'base/api',
            'users' => [
                'amolchanov',
            ],
            'platforms' => []
        ]);

        $expectedResponseCode = 400;
        $expectedErrorMessage = 'Missing required parameters: platforms';

        $I->seeResponseCodeIs($expectedResponseCode);
        $I->see($expectedErrorMessage);
    }

    /**
     * Test case for api with non empty platform list
     *
     * @return void
     */
    public function cestSeveralPlatforms(\FunctionalTester $I)
    {
        $I->amOnPage([
            'base/api',
            'users' => [
                'fusionjack',
            ],
            'platforms' => [
                'github',
                'gitlab'
            ]
        ]);

        $expected = json_decode('[{
            "name": "fusionjack",
            "platform": "gitlab",
            "total-rating": 314.75,
            "repos": [],
            "repo": [{
                "name": "adhell3",
                "fork-count": 180,
                "start-count": 469,
                "rating": 297.25
            }, {
                "name": "adhell3-scripts",
                "fork-count": 7,
                "start-count": 23,
                "rating": 12.75
            }, {
                "name": "adhell3-hosts",
                "fork-count": 3,
                "start-count": 7,
                "rating": 4.75
            }]
            }, 
            {
            "name": "fusionjack",
            "platform": "github",
            "total-rating": 1.6666666666666667,
            "repos": [],
            "repo": [{
                "name": "slimota",
                "fork-count": 1,
                "start-count": 2,
                "watcher-count": 2,
                "rating": 1.6666666666666667
            }]
        }]');

        $I->assertEquals($expected, json_decode($I->grabPageSource()));
    }

    /**
     * Test case for api with non empty user list
     *
     * @return void
     */
    public function cestSeveralUsers(\FunctionalTester $I)
    {
        $I->amOnPage([
            'base/api',
            'users' => [
                'fusionjack',
                'kfr'
            ],
            'platforms' => [
                'github',

            ]
        ]);

        $expected = json_decode('[{
            "name": "fusionjack",
            "platform": "github",
            "total-rating": 1.6666666666666667,
            "repos": [],
            "repo": [{
                "name": "slimota",
                "fork-count": 1,
                "start-count": 2,
                "watcher-count": 2,
                "rating": 1.6666666666666667
            }]
            }, {
                "name": "kfr",
                "platform": "github",
                "total-rating": 1,
                "repos": [],
                "repo": [{
                    "name": "cards",
                    "fork-count": 0,
                    "start-count": 0,
                    "watcher-count": 0,
                    "rating": 0
                }, {
                    "name": "kf-cli",
                    "fork-count": 0,
                    "start-count": 1,
                    "watcher-count": 1,
                    "rating": 0.5
                }, {
                    "name": "UdaciCards",
                    "fork-count": 0,
                    "start-count": 0,
                    "watcher-count": 0,
                    "rating": 0
                }, {
                    "name": "unikgen",
                    "fork-count": 0,
                    "start-count": 1,
                    "watcher-count": 1,
                    "rating": 0.5
                }]
            }]');

        $I->assertEquals($expected, json_decode($I->grabPageSource()));
    }

    /**
     * Test case for api with unknown platform in list
     *
     * @return void
     */
    public function cestUnknownPlatforms(\FunctionalTester $I)
    {
        try {
            $I->amOnPage([
                'base/api',
                'users' => [
                    'fusionjack'
                ],
                'platforms' => [
                    'unknownplatform',
                ]
            ]);
        } catch (Exception $e) {
        }

        $expectedException = 'LogicException';

        $I->assertEquals($expectedException, get_class($e));
    }

    /**
     * Test case for api with unknown user in list
     *
     * @return void
     */
    public function cestUnknownUsers(\FunctionalTester $I)
    {
        $I->amOnPage([
            'base/api',
            'users' => [
                'unknownuser1'
            ],
            'platforms' => [
                'github',
            ]
        ]);

        $expected = json_decode('[]');

        $I->assertEquals($expected, json_decode($I->grabPageSource()));
    }

    /**
     * Test case for api with mixed (unknown, real) users and non empty platform list
     *
     * @return void
     */
    public function cestMixedUsers(\FunctionalTester $I)
    {
        $I->amOnPage([
            'base/api',
            'users' => [
                'unknownuser1',
                'fusionjack',
                'kfr',
            ],
            'platforms' => [
                'github',
            ]
        ]);

        $expected = json_decode('[{
                "name": "fusionjack",
                "platform": "github",
                "total-rating": 1.6666666666666667,
                "repos": [],
                "repo": [{
                    "name": "slimota",
                    "fork-count": 1,
                    "start-count": 2,
                    "watcher-count": 2,
                    "rating": 1.6666666666666667
                }]
            },
            {
                "name": "kfr",
                "platform": "github",
                "total-rating": 1,
                "repos": [],
                "repo": [{
                        "name": "cards",
                        "fork-count": 0,
                        "start-count": 0,
                        "watcher-count": 0,
                        "rating": 0
                    },
                    {
                        "name": "kf-cli",
                        "fork-count": 0,
                        "start-count": 1,
                        "watcher-count": 1,
                        "rating": 0.5
                    },
                    {
                        "name": "UdaciCards",
                        "fork-count": 0,
                        "start-count": 0,
                        "watcher-count": 0,
                        "rating": 0
                    },
                    {
                        "name": "unikgen",
                        "fork-count": 0,
                        "start-count": 1,
                        "watcher-count": 1,
                        "rating": 0.5
                    }
                ]
            }]'
        );

        $I->assertEquals($expected, json_decode($I->grabPageSource()));
    }

    /**
     * Test case for api with mixed (github, gitlab, bitbucket) platforms and non empty user list
     *
     * @return void
     *
     * Note: There is a problem getting Bitbucket user information by 'userName'.
     * Bitbucket API returns an empty array.
     * But if instead of 'userName' send the 'UUID' of the user, then Bitbucket API correctly returns user information.
     */
    public function cestMixedPlatforms(\FunctionalTester $I)
    {
        $I->amOnPage([
            'base/api',
            'users' => [
                "{30118b3d-3199-47e3-a32f-def47583865f}",
                'fusionjack'
            ],
            'platforms' => [
                'github',
                'gitlab',
                'bitbucket'
            ]
        ]);

        $expected = json_decode('[{
                "name": "fusionjack",
                "platform": "gitlab",
                "total-rating": 314.75,
                "repos": [],
                "repo": [{
                    "name": "adhell3",
                    "fork-count": 180,
                    "start-count": 469,
                    "rating": 297.25
                }, {
                    "name": "adhell3-scripts",
                    "fork-count": 7,
                    "start-count": 23,
                    "rating": 12.75
                }, {
                    "name": "adhell3-hosts",
                    "fork-count": 3,
                    "start-count": 7,
                    "rating": 4.75
                }]
            }, {
                "name": "xdevs23",
                "platform": "bitbucket",
                "total-rating": 3,
                "repos": [],
                "repo": [{
                    "name": "aarch64-linux-android-7.0",
                    "fork-count": 1,
                    "watcher-count": 1,
                    "rating": 1.5
                }, {
                    "name": "zodaai_toolchains_prebuilt_linux-x64_gcc_x86_64-pc-linux-gnu",
                    "fork-count": 1,
                    "watcher-count": 1,
                    "rating": 1.5
                }]
            }, {
                "name": "fusionjack",
                "platform": "github",
                "total-rating": 1.6666666666666667,
                "repos": [],
                "repo": [{
                    "name": "slimota",
                    "fork-count": 1,
                    "start-count": 2,
                    "watcher-count": 2,
                    "rating": 1.6666666666666667
                }]
            }]'
        );

        $I->assertEquals($expected, json_decode($I->grabPageSource()));
    }
}