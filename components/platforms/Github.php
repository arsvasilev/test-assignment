<?php

namespace app\components\platforms;

use Github\Client;
use yii\base;

use app\interfaces;
use app\models;

/**
 * Github implementation of interfaces\IPlatform
 * 
 * @see https://github.com/KnpLabs/php-github-api/
 */
class Github extends base\Component implements interfaces\IPlatform
{
    /**
     * Github platform ctor
     *
     * @param mixed $config
     */
    public function __construct($config)
    {
        $this->client = new Client();
        parent::__construct($config);
    }

    /**
     * @inheritDoc
     */
    public function findUserInfo(string $userName) : ? interfaces\IUser
    {
        /**
         * @see https://github.com/KnpLabs/php-github-api/blob/master/doc/users.md#search-for-users-by-keyword
         */
        $response = $this->client->api('user')->find($userName);
        foreach ($response['users'] as $user) {
            if ($user['username'] == $userName) {
                return new models\User(
                    $user['username'],
                    $user['username'],
                    'github'
                );
            }
        }
        return null;
    }

    /**
     * @inheritDoc
     */
    public function findUserRepos(string $user) : array
    {
        /**
         * @see https://github.com/KnpLabs/php-github-api/blob/master/doc/repos.md#get-the-repositories-of-a-specific-user
         */
        $result = [];
        $response = $this->client->api('user')->repositories($user);
        foreach ($response as $repo) {
            $result[] = new models\GithubRepo(
                $repo['name'],
                $repo['forks_count'],
                $repo['stargazers_count'],
                $repo['watchers_count']
            );
        }
        return $result;
    }

    /**
     * @var Github\Client
     */
    private $client;
}
