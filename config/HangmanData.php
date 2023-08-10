<?php

/**
 * @copyright Dennis Reilard alias hhunderter
 * @package ilch
 */

namespace Modules\Hangman\Config;

use Modules\Hangman\Mappers\Words as WordsMapper;
use Modules\Hangman\Models\Words as WordsModel;

class HangmanData
{
    /**
     * @var array
     */
    private $dataWords = [
        ['text' => 'dog', 'difficulty' => 1, 'locale' => 'en_EN'],
        ['text' => 'cat', 'difficulty' => 1, 'locale' => 'en_EN'],
        ['text' => 'fish', 'difficulty' => 1, 'locale' => 'en_EN'],
        ['text' => 'horse', 'difficulty' => 1, 'locale' => 'en_EN'],
        ['text' => 'donkey', 'difficulty' => 1, 'locale' => 'en_EN'],
        ['text' => 'cow', 'difficulty' => 1, 'locale' => 'en_EN'],
        ['text' => 'mule', 'difficulty' => 1, 'locale' => 'en_EN'],
        ['text' => 'lizard', 'difficulty' => 1, 'locale' => 'en_EN'],
        ['text' => 'leather belt', 'difficulty' => 2, 'locale' => 'en_EN'],
        ['text' => 'blue suit', 'difficulty' => 2, 'locale' => 'en_EN'],
        ['text' => 'crochet shawl', 'difficulty' => 2, 'locale' => 'en_EN'],
        ['text' => 'denim jeans', 'difficulty' => 2, 'locale' => 'en_EN'],
        ['text' => 'black tuxedo', 'difficulty' => 2, 'locale' => 'en_EN'],
        ['text' => 'seamstress', 'difficulty' => 2, 'locale' => 'en_EN'],
        ['text' => 'sibilant', 'difficulty' => 3, 'locale' => 'en_EN'],
        ['text' => 'cicatrix', 'difficulty' => 3, 'locale' => 'en_EN'],
        ['text' => 'pother', 'difficulty' => 3, 'locale' => 'en_EN'],
        ['text' => 'sudorific', 'difficulty' => 3, 'locale' => 'en_EN'],
        ['text' => 'person', 'difficulty' => 3, 'locale' => 'en_EN'],
        ['text' => 'besot', 'difficulty' => 3, 'locale' => 'en_EN'],
        ['text' => 'fulcrum', 'difficulty' => 3, 'locale' => 'en_EN'],

        ['text' => 'Hund', 'difficulty' => 1, 'locale' => 'de_DE'],
        ['text' => 'Katze', 'difficulty' => 1, 'locale' => 'de_DE'],
        ['text' => 'Fische', 'difficulty' => 1, 'locale' => 'de_DE'],
        ['text' => 'Pferd', 'difficulty' => 1, 'locale' => 'de_DE'],
        ['text' => 'Esel', 'difficulty' => 1, 'locale' => 'de_DE'],
        ['text' => 'Kuh', 'difficulty' => 1, 'locale' => 'de_DE'],
        ['text' => 'Maultier', 'difficulty' => 1, 'locale' => 'de_DE'],
        ['text' => 'Eidechse', 'difficulty' => 1, 'locale' => 'de_DE'],
        ['text' => 'Ledergürtel', 'difficulty' => 2, 'locale' => 'de_DE'],
        ['text' => 'Blauer Anzug', 'difficulty' => 2, 'locale' => 'de_DE'],
        ['text' => 'gehäkelter Schal', 'difficulty' => 2, 'locale' => 'de_DE'],
        ['text' => 'Denim Jeans', 'difficulty' => 2, 'locale' => 'de_DE'],
        ['text' => 'schwarzer Smoking', 'difficulty' => 2, 'locale' => 'de_DE'],
        ['text' => 'Näherin', 'difficulty' => 2, 'locale' => 'de_DE'],
        ['text' => 'Zischlaut', 'difficulty' => 3, 'locale' => 'de_DE'],
        ['text' => 'Narbe', 'difficulty' => 3, 'locale' => 'de_DE'],
        ['text' => 'Topf', 'difficulty' => 3, 'locale' => 'de_DE'],
        ['text' => 'schweißtreibend', 'difficulty' => 3, 'locale' => 'de_DE'],
        ['text' => 'Mensch', 'difficulty' => 3, 'locale' => 'de_DE'],
        ['text' => 'berauschen', 'difficulty' => 3, 'locale' => 'de_DE'],
        ['text' => 'Drehpunkt', 'difficulty' => 3, 'locale' => 'de_DE'],

    ];

    /**
     * @return array
     */
    public function getDataWords(): array
    {
        return $this->dataWords;
    }

    /**
     * @param array $dataWords
     * @return $this
     */
    public function setDataWords(array $dataWords): HangmanData
    {
        $this->dataWords = $dataWords;
        return $this;
    }

    /**
     * @param string $dataWord
     * @param int|null $key
     * @return $this
     */
    public function addDataWord(string $dataWord, ?int $key = null): HangmanData
    {
        if ($key) {
            $this->dataWords[$key] = $dataWord;
        } else {
            $this->dataWords[] = $dataWord;
        }
        return $this;
    }

    /**
     * @param bool $check
     * @return $this
     */
    public function wordsData(bool $check = false): HangmanData
    {
        $wordsMapper = new WordsMapper();

        $dataWords = $wordsMapper->loadJsonFile();
        if ($dataWords) {
            $this->setDataWords($dataWords);
        }

        foreach ($this->getDataWords() as $entrie) {
            $entryModel = new WordsModel();

            $entryModel->setByArray($entrie);

            if ($check) {
                if (!$wordsMapper->getEntryByText($entryModel)) {
                    $wordsMapper->save($entryModel);
                }
            } else {
                $wordsMapper->save($entryModel);
            }
        }
        return $this;
    }

    /**
     * @param bool $check
     * @return $this
     */
    public function importData(bool $check = false): HangmanData
    {
        $this->wordsData($check);
        return $this;
    }
}
