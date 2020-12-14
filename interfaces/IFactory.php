<?php

namespace app\interfaces;

/**
 * Represent factory for platform instance creation
 */
interface IFactory
{
    /**
     * Create platform instance
     *
     * @param string $name
     * @return IPlatform
     * @throws LogicException
     */
    public function create(string $name) : IPlatform;
}
