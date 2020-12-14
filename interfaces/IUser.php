<?php

namespace app\interfaces;

/**
 * Represent user model
 */
interface IUser
{
    /**
     * Return identifier of user
     *
     * @return string
     */
    public function getIdentifier() : string;

    /**
     * Return name of user
     *
     * @return string
     */
    public function getName() : string;

    /**
     * Return platform of user
     *
     * @return string
     */
    public function getPlatform() : string;

    /**
     * Return total rating of user
     *
     * @return float
     */
    public function getTotalRating() : float;

    /**
     * Return all user data in array representation
     *
     * @return array
     */
    public function getData() : array;

    /**
     * Add repo to user
     *
     * @param IRepo[] $repos
     * @return void
     * @throws LogicException
     */
    public function addRepos(array $repos);
}
