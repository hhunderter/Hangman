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

    public function getFillData()
    {
        if (file_exists(dirname(__FILE__).'/dataWords.json')) {
            $content = file_get_contents(dirname(__FILE__).'/dataWords.json');
            if ($content) {
                $this->dataWords = json_decode($content, true);
            }
        }

        $wordsMapper = new WordsMapper();
        foreach ($this->dataWords ?? [] as $entrie) {
            $entryModel = new WordsModel();

            $entryModel->setByArray($entrie);

            $wordsMapper->save($entryModel);
        }

    }
}
