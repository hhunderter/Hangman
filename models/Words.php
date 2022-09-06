<?php
/**
 * @copyright Dennis Reilard alias hhunderter
 * @package ilch
 */

namespace Modules\Hangman\Models;

class Words extends \Ilch\Model
{
    /**
     * @var int
     */
    protected $id = 0;

    /**
     * @var string
     */
    protected $text = '';
    
    /**
     * @var int
     */
    protected $difficulty = 1;

    /**
     * @var string
     */
    protected $locale = '';

    /**
     * @param array $entries
     * @return $this
     */
    public function setByArray(array $entries): Words
    {
        if (isset($entries['id'])) {
            $this->setId($entries['id']);
        }
        if (isset($entries['text'])) {
            $this->setText($entries['text']);
        }
        if (isset($entries['difficulty'])) {
            $this->setDifficulty($entries['difficulty']);
        }
        if (isset($entries['locale'])) {
            $this->setLocale($entries['locale']);
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
    public function setId(int $id): Words
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @param string $text
     * @return $this
     */
    public function setText(string $text): Words
    {
        $this->text = $text;
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
    public function setDifficulty(int $difficulty): Words
    {
        $this->difficulty = $difficulty;
        return $this;
    }

    /**
     * @return string
     */
    public function getLocale(): string
    {
        return $this->locale;
    }

    /**
     * @param string $locale
     * @return $this
     */
    public function setLocale(string $locale): Words
    {
        $this->locale = $locale;
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
                'text' => $this->getText(),
                'difficulty' => $this->getDifficulty(),
                'locale' => $this->getLocale(),
            ]
        );
    }
}
