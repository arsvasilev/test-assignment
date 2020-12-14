<?php

namespace app\components\platforms;

use Bitbucket\API;
use yii\base;

use app\interfaces;
use app\models;

/**
 * Bitbucket implementation of interfaces\IPlatform
 * 
 * @see https://bitbucket.org/gentlero/bitbucket-api/src/
 */
class Bitbucket extends base\Component implements interfaces\IPlatform
{

    /**
     * Bitbucket platform ctor
     * 
     * @param mixed $config
     */
    public function __construct($config)
    {
        $this->api = new Api\Users();
        parent::__construct($config);
    }

    /**
     * @inheritDoc
     */
    public function findUserInfo(string $userName) : ? interfaces\IUser
    {
        /**
         * @see https://bitbucket.org/gentlero/bitbucket-api/src/8aa84ffbe0846da6a05fc89cdfbc159c622e9a4e/lib/Bitbucket/API/Users.php?at=develop&fileviewer=file-view-default#Users.php-31
         */
        $response = $this->api->get($userName);
        $response = json_decode($response->getContent(), true);
        if (array_key_exists('nickname', $response)) {
            return new models\User(
                $response['nickname'],
                $response['nickname'],
                'bitbucket'
            );
        }
        return null;
    }

    /**
     * @inheritDoc
     */
    public function findUserRepos(string $user) : array
    {
        /**
         * @see https://bitbucket.org/gentlero/bitbucket-api/src/8aa84ffbe0846da6a05fc89cdfbc159c622e9a4e/lib/Bitbucket/API/Users.php?at=develop&fileviewer=file-view-default#lines-73
         */
        $result = [];
        for ($i = 1;; ++$i) {
            $response = $this->api->repositories("$user?page=$i");
            $response = json_decode($response->getContent(), true);
            if (!count($response['values'])) {
                break;
            }
            foreach ($response['values'] as $repo) {
                $result[] = new models\BitbucketRepo(
                    $repo['name'],
                    count($repo['links']['forks']),
                    count($repo['links']['watchers'])
                );
            }
        }
        return $result;
    }

    /**
     * @var Bitbucket\API\Users
     */
    private $api;
}
