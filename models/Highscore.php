<?php

/**
 * @copyright Dennis Reilard alias hhunderter
 * @package ilch
 */

namespace Modules\Hangman\Models;

class Highscore extends \Ilch\Model
{
    /**
     * @var int
     */
    protected $id = 0;

    /**
     * @var int
     */
    protected $userId = 0;

    /**
     * @var int
     */
    protected $score = 0;

    /**
     * @var int
     */
    protected $games = 0;

    /**
     * @param array $entries
     * @return $this
     */
    public function setByArray(array $entries): Highscore
    {
        if (isset($entries['id'])) {
            $this->setId($entries['id']);
        }
        if (isset($entries['user_id'])) {
            $this->setUserId($entries['user_id']);
        }
        if (isset($entries['score'])) {
            $this->setScore($entries['score']);
        }
        if (isset($entries['games'])) {
            $this->setGames($entries['games']);
        }
        return $this;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function setId(int $id): Highscore
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     * @return $this
     */
    public function setUserId(int $userId): Highscore
    {
        $this->userId = $userId;
        return $this;
    }

    /**
     * @return int
     */
    public function getScore(): int
    {
        return $this->score;
    }

    /**
     * @param int $score
     * @return $this
     */
    public function setScore(int $score): Highscore
    {
        $this->score = $score;
        return $this;
    }

    /**
     * @param int $score
     * @return $this
     */
    public function addScore(int $score): Highscore
    {
        $this->score += $score;
        return $this;
    }

    /**
     * @return int
     */
    public function getGames(): int
    {
        return $this->games;
    }

    /**
     * @param int $games
     * @return $this
     */
    public function setGames(int $games): Highscore
    {
        $this->games = $games;
        return $this;
    }

    /**
     * @param int $games
     * @return $this
     */
    public function addGames(int $games = 1): Highscore
    {
        $this->games += $games;
        return $this;
    }

    /**
     * Gets the Array of Model.
     *
     * @param bool $withId
     * @return array
     */
    public function getArray(bool $withId = true): array
    {
        return array_merge(
            ($withId ? ['id' => $this->getId()] : []),
            [
                'user_id' => $this->GetUserId(),
                'score' => $this->getScore(),
                'games' => $this->getGames(),
            ]
        );
    }
}
