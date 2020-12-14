<?php

namespace app\models;

use \yii\base;

use app\interfaces;

/**
 * BitbucketRepo implementation of interfaces\Repo
 */
class BitbucketRepo extends base\Model implements interfaces\IRepo
{
    /**
     * BitbucketRepo ctor
     *
     * @param string $name
     * @param int $forkCount
     * @param int $watcherCount
     */
    public function __construct($name, $forkCount, $watcherCount)
    {
        $this->name = $name;
        $this->forkCount = $forkCount;
        $this->watcherCount = $watcherCount;
    }

    /**
     * @inheritDoc
     */
    public function __toString() : string
    {
        return sprintf(
            "%-75s %4d ⇅ %6s %4d 👁️",
            $this->name,
            $this->forkCount,
            "",
            $this->watcherCount
        );
    }

    /**
     * @inheritDoc
     */
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    public function getForkCount() : int
    {
        return $this->forkCount;
    }

    /**
     * @inheritDoc
     */
    public function getStarCount() : int
    {
        return 0;
    }

    /**
     * @inheritDoc
     */
    public function getWatcherCount() : int
    {
        return $this->watcherCount;
    }

    /**
     * @inheritDoc
     */
    public function getRating() : float
    {
        return (($this->forkCount * 2.0) + $this->watcherCount) / 2.0;
    }

    /**
     * @inheritDoc
     */
    public function getData() : array
    {
        return [
            'name' => $this->name,
            'fork-count' => $this->forkCount,
            'watcher-count' => $this->watcherCount,
            'rating' => $this->rating,
        ];
    }

    /**
     * @var string
     */
    private $name;

    /**
     * @var int
     */
    private $forkCount;

    /**
     * @var int
     */
    private $watcherCount;
}
