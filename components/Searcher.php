<?php

namespace app\components;

use yii\base;

use app\interfaces;

/**
 * Searcher implementation of interfaces\ISearcher
 */
class Searcher extends base\Component implements interfaces\ISearcher
{
    /**
     * @inheritDoc
     */
    public function search(array $platforms, array $userNames) : array
    {
        $users = [];
        foreach ($userNames as $userName) {
            foreach ($platforms as $platform) {
                $user = $platform->findUserInfo($userName);
                if ($user instanceof interfaces\IUser) {
                    $id = $user->getIdentifier();
                    $repos = $platform->findUserRepos($id);
                    if (count($repos)) {
                        $user->addRepos($repos);
                        $users[] = $user;
                    }
                }
            }
        }
        usort($users, function ($user1, $user2) {
            return $user2->getTotalRating() - $user1->getTotalRating();
        });
        return $users;
    }
}
