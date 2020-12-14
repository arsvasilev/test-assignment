<?php

namespace app\components;

use yii\base;

use app\interfaces;
use app\components\platforms;

/**
 * Factory implementation of interfaces\IFactory
 */
class Factory extends base\Component implements interfaces\IFactory
{
    const PLATFORM_GITHUB = 'github';
    const PLATFORM_GITLAB = 'gitlab';
    const PLATFORM_BITBUCKET = 'bitbucket';

    /**
     * @inheritDoc
     */
    public function create(string $name) : interfaces\IPlatform
    {
        if (array_key_exists($name, $this->cahce)) {
            return $this->cahce[$name];
        }

        switch ($name) {
            case self::PLATFORM_GITHUB:
                $platform = new platforms\Github([]);
                break;

            case self::PLATFORM_GITLAB:
                $platform = new platforms\Gitlab([]);
                break;

            case self::PLATFORM_BITBUCKET:
                $platform = new platforms\Bitbucket([]);
                break;

            default:
                throw new \LogicException();
        }

        $this->cahce[$name] = $platform;
        return $platform;
    }

    /**
     * @var array
     */
    private $cahce = [];
}
