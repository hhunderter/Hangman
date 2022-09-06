<?php
/**
 * @copyright Dennis Reilard alias hhunderter
 * @package ilch
 */

namespace Modules\Hangman\Models;

class Game extends \Ilch\Model
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
     * @var string
     */
    protected $sessionId = '';
    
    /**
     * @var string
     */
    protected $lastActivity = '';
    
    /**
     * @var int
     */
    protected $score = 0;
    
    /**
     * @var int
     */
    protected $health = -1;
    
    /**
     * @var int
     */
    protected $wordId = 0;
    
    /**
     * @var int
     */
    protected $difficulty = 1;
    
    /**
     * @var string
     */
    protected $letters = '';

    /**
     * @param array $entries
     * @return $this
     */
    public function setByArray(array $entries): Game
    {
        if (isset($entries['id'])) {
            $this->setId($entries['id']);
        }
        if (isset($entries['user_id'])) {
            $this->setUserId($entries['user_id']);
        }
        if (isset($entries['session_id'])) {
            $this->setSessionId($entries['session_id']);
        }
        if (isset($entries['last_activity'])) {
            $this->setLastActivity($entries['last_activity']);
        }
        if (isset($entries['score'])) {
            $this->setScore($entries['score']);
        }
        if (isset($entries['health'])) {
            $this->setHealth($entries['health']);
        }
        if (isset($entries['word_id'])) {
            $this->setWordId($entries['word_id']);
        }
        if (isset($entries['difficulty'])) {
            $this->setDifficulty($entries['difficulty']);
        }
        if (isset($entries['letters'])) {
            $this->setLetters($entries['letters']);
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
    public function setId(int $id): Game
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
    public function setUserId(int $userId): Game
    {
        $this->userId = $userId;
        return $this;
    }

    /**
     * @return string
     */
    public function getSessionId(): string
    {
        return $this->sessionId;
    }

    /**
     * @param string $sessionId
     * @return $this
     */
    public function setSessionId(string $sessionId): Game
    {
        $this->sessionId = $sessionId;
        return $this;
    }

    /**
     * @return string
     */
    public function getLastActivity(): string
    {
        return $this->lastActivity;
    }

    /**
     * @param string $lastActivity
     * @return $this
     */
    public function setLastActivity(string $lastActivity): Game
    {
        $this->lastActivity = $lastActivity;
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
    public function setScore(int $score): Game
    {
        $this->score = $score;
        return $this;
    }

    /**
     * @param int $score
     * @return $this
     */
    public function addScore(int $score): Game
    {
        $this->score += $score;
        return $this;
    }

    /**
     * @return int
     */
    public function getHealth(): int
    {
        return $this->health;
    }

    /**
     * @param int $health
     * @return $this
     */
    public function setHealth(int $health): Game
    {
        $this->health = $health;
        return $this;
    }

    /**
     * @param int $health
     * @return $this
     */
    public function addHealth(int $health): Game
    {
        $this->health += $health;
        return $this;
    }

    /**
     * @return int
     */
    public function getWordId(): int
    {
        return $this->wordId;
    }

    /**
     * @param int $wordId
     * @return $this
     */
    public function setWordId(int $wordId): Game
    {
        $this->wordId = $wordId;
        return $this;
    }

    /**
     * @return int
     */
    public function getDifficulty(): int
    {
        return $this->difficulty;
    }

    /**
     * @param int $difficulty
     * @return $this
     */
    public function setDifficulty(int $difficulty): Game
    {
        $this->difficulty = $difficulty;
        return $this;
    }

    /**
     * @return string
     */
    public function getLetters(): string
    {
        return $this->letters;
    }

    /**
     * @param string $letters
     * @return $this
     */
    public function setLetters(string $letters): Game
    {
        $this->letters = $letters;
        return $this;
    }

    /**
     * @param string $letter
     * @return $this
     */
    public function addLetter(string $letter): Game
    {
        if (!empty($this->letters)) {
            $this->letters .= ',';
        }
        $this->letters .= $letter;
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
                'user_id' => $this->getUserId(),
                'session_id' => $this->getSessionId(),
                'last_activity' => $this->getLastActivity(),
                'score' => $this->getScore(),
                'health' => $this->getHealth(),
                'word_id' => $this->getWordId(),
                'difficulty' => $this->getDifficulty(),
                'letters' => $this->getLetters(),
            ]
        );
    }

}
