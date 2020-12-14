<?php

namespace app\models;

use \yii\base;
use yii\helpers\Html;

use app\interfaces;

/**
 * User implementation of interfaces\IUser
 */
class User extends base\Model implements interfaces\IUser
{
    /**
     * User ctor
     *
     * @param string $identifier
     * @param string $name
     * @param string $platform
     */
    public function __construct(string $identifier, string $name, string $platform)
    {
        $this->identifier = $identifier;
        $this->name = $name;
        $this->platform = $platform;
        $this->repositories = [];
    }

    /**
     * @inheritDoc
     */
    public function __toString() : string
    {
        $result = sprintf(
            "%-75s %19d 🏆\n%'=98s\n",
            $this->fullName,
            $this->totalRating,
            ""
        );
        foreach ($this->repositories as $repository) {
            $result .= (string)$repository . "\n";
        }
        return $result;
    }

    /**
     * @inheritDoc
     */
    public function getIdentifier() : string
    {
        return $this->identifier;
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
    public function getPlatform() : string
    {
        return $this->platform;
    }

    /**
     * @inheritDoc
     */
    public function getFullName() : string
    {
        return "{$this->name} ({$this->platform})";
    }

    /**
     * @inheritDoc
     */
    public function getTotalRating() : float
    {
        $rating = 0.0;
        foreach ($this->repositories as $repository) {
            $rating += $repository->getRating();
        }
        return $rating;
    }

    /**
     * @inheritDoc
     */
    public function getData() : array
    {
        $data = [
            'name' => $this->name,
            'platform' => $this->platform,
            'total-rating' => $this->totalRating,
            'repos' => [],
        ];
        foreach ($this->repositories as $repository) {
            $data['repo'][] = $repository->getData();
        }
        return $data;
    }

    /**
     * @inheritDoc
     */
    public function addRepos(array $repos)
    {
        $this->repositories = array_merge($this->repositories, $repos);
        usort($this->repositories, function ($repo1, $repo2) {
            if (!($repo1 instanceof interfaces\IRepo) ||
                !($repo2 instanceof interfaces\IRepo)) {
                throw new \LogicException();
            }

            return $repo2->getRating() - $repo1->getRating();
        });
    }

    /**
     * @var string
     */
    private $identifier;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $platform;

    /**
     * @var interfaces\Repo[]
     */
    private $repositories;
}
