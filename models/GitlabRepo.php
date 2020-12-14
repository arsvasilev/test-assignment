<?php

namespace app\models;

use \yii\base;

use app\interfaces;

/**
 * GitlabRepo implementation of interfaces\Repo
 */
class GitlabRepo extends base\Model implements interfaces\IRepo
{
    /**
     * GitlabRepo ctor
     *
     * @param string $name
     * @param int $forkCount
     * @param int $startCount
     */
    public function __construct($name, $forkCount, $startCount)
    {
        $this->name = $name;
        $this->forkCount = $forkCount;
        $this->startCount = $startCount;
    }

    /**
     * @inheritDoc
     */
    public function __toString() : string
    {
        return sprintf(
            "%-75s %4d ⇅ %4d ★",
            $this->name,
            $this->forkCount,
            $this->startCount
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
        return $this->startCount;
    }

    /**
     * @inheritDoc
     */
    public function getWatcherCount() : int
    {
        return 0;
    }

    /**
     * @inheritDoc
     */
    public function getRating() : float
    {
        return (($this->forkCount * 2.0) + ($this->startCount / 2.0)) / 2.0;
    }

    /**
     * @inheritDoc
     */
    public function getData() : array
    {
        return [
            'name' => $this->name,
            'fork-count' => $this->forkCount,
            'start-count' => $this->startCount,
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
    private $startCount;
}
