<?php

namespace app\interfaces;

/**
 * Represent platform for user and repo searching
 */
interface IPlatform
{
    /**
     * Find user model by userName
     *
     * @param string $userName
     * @return User
     */
    public function findUserInfo(string $userName) : ? IUser;

    /**
     * Find repos by user identifier
     *
     * @param string $userId
     * @return array
     */
    public function findUserRepos(string $user) : array;
}
