<?php

namespace app\interfaces;

/**
 * Represent interface decorator for searching via several platforms
 */
interface ISearcher
{
    /**
     * Return user models 
     *
     * @param IPlatform[] $platforms
     * @param array $userNames
     * @return array
     */
    public function search(array $platforms, array $userNames) : array;
}
