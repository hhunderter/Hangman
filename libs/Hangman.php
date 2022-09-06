<?php

namespace Modules\Hangman\Libs;

use Modules\Hangman\Mappers\Game as GameMapper;
use Modules\Hangman\Models\Game as GameModel;

use Modules\Hangman\Mappers\Words as WordsMapper;
use Modules\Hangman\Models\Words as WordsModel;

use Modules\Hangman\Mappers\Highscore as HighscoreMapper;
use Modules\Hangman\Models\Highscore as HighscoreModel;

class Hangman {
    /**
     * @var \Ilch\Translator|null
     */
    var $translator = null;
    /**
     * @var \Modules\User\Models\User|null
     */
    var $user = null;
    /**
     * @var GameModel|null
     */
    var $gameModel = null;

    /**
     * @var array
     */
    var $wordLetters = [];	//array - array of the letters in the word

    /**
     * @var bool
     */
    var $dbfail = false;		//bool - toggle game won

    /**
     * @var bool
     */
    var $won = false;		//bool - toggle game won
    /**
     * @var bool
     */
    var $over = false;		//bool - toggle game over
    /**
     * @var bool
     */
    var $locked = false;		//bool - toggle game over
    /**
     * @var int
     */
    var $guesses = 6;
    /**
     * @var bool
     */
    var $gameChange = false;

    var $difficultyTypes = [1 => 'easy',
        2 => 'medium',
        3 => 'hard',
    ];

    var $alphabet = [		//array - all letters in the alphabet
        "a", "b", "c", "d", "e", "f", "g", "h",
        "i", "j", "k", "l", "m", "n", "o", "p",
        "q", "r", "s", "t", "u", "v", "w", "x",
        "y", "z",
    ];

    /**
     * @return null|\Modules\User\Models\User
     */
    private function getUser(): ?\Modules\User\Models\User
    {
        return $this->user;
    }

    /**
     * @param null|\Modules\User\Models\User $User
     * @return $this
     */
    private function setUser(?\Modules\User\Models\User $User): Hangman
    {
        $this->user = $User;
        return $this;
    }

    /**
     * @return \Ilch\Translator
     */
    private function getTranslator(): \Ilch\Translator
    {
        return $this->translator;
    }

    /**
     * @param \Ilch\Translator $translator
     * @return $this
     */
    private function setTranslator(\Ilch\Translator $translator): Hangman
    {
        $this->translator = $translator;
        return $this;
    }

    /**
     * @return GameModel
     */
    private function getGameModel(): GameModel
    {
        if (!$this->gameModel) {
            $this->gameModel = new GameModel();
        }
        return $this->gameModel;
    }

    /**
     * @param GameModel|null $gameModel
     * @return $this
     */
    private function setGameModel(?GameModel $gameModel): Hangman
    {
        $this->gameModel = $gameModel;
        return $this;
    }

    /**
     * @param int|null $key
     * @return string|null
     */
    private function getWordLetter(int $key = null): ?string
    {
        return $this->wordLetters[$key] ?? null;
    }

    /**
     * @return array
     */
    private function getWordLetters(): array
    {
        return $this->wordLetters;
    }

    /**
     * @param array $letters
     * @return $this
     */
    private function setWordLetters(array $letters): Hangman
    {
        $this->wordLetters = $letters;
        return $this;
    }

    /**
     * @param string $letter
     * @param int|null $key
     * @return $this
     */
    private function addWordLetter(string $letter, ?int $key = null): Hangman
    {
        if ($key) {
            $this->wordLetters[$key] = $letter;
        } else {
            $this->wordLetters[] = $letter;
        }
        return $this;
    }

    /**
     * @return int
     */
    private function getCountWordLetters(): int
    {
        return count($this->wordLetters);
    }

    /**
     * @return bool
     */
    private function getDBfail(): bool
    {
        return $this->dbfail;
    }

    /**
     * @param bool $won
     * @return $this
     */
    private function setDBfail(bool $dbfail = true): Hangman
    {
        $this->dbfail = $dbfail;
        return $this;
    }

    /**
     * @return bool
     */
    private function getWon(): bool
    {
        return $this->won;
    }

    /**
     * @param bool $won
     * @return $this
     */
    private function setWon(bool $won = true): Hangman
    {
        $this->won = $won;
        return $this;
    }

    /**
     * @return bool
     */
    private function getOver(): bool
    {
        return $this->over;
    }

    /**
     * @param bool $over
     * @return $this
     */
    public function setOver(bool $over = true): Hangman
    {
        $this->over = $over;
        return $this;
    }

    /**
     * @return bool
     */
    private function getLocked(): bool
    {
        return $this->locked;
    }

    /**
     * @param bool $locked
     * @return $this
     */
    public function setLocked(bool $locked = true): Hangman
    {
        $this->locked = $locked;
        return $this;
    }

    /**
     * @return int
     */
    public function getGuesses(): int
    {
        return $this->guesses;
    }

    /**
     * @return bool
     */
    public function getGameChange(): bool
    {
        return $this->gameChange;
    }

    /**
     * @param bool $gameChange
     * @return $this
     */
    public function setGameChange(bool $gameChange = true): Hangman
    {
        $this->gameChange = $gameChange;
        return $this;
    }

    /**
     * @param int $key
     * @return string|null
     */
    public function getDifficultyType(int $key): ?string
    {
        return $this->difficultyTypes[$key] ?? null;
    }

    /**
     * @return array
     */
    public function getDifficultyTypes(): array
    {
        return $this->difficultyTypes;
    }

    /**
     * @param array $difficultyTypes
     * @return $this
     */
    public function setDifficultyTypes(array $difficultyTypes): Hangman
    {
        $this->difficultyTypes = $difficultyTypes;
        return $this;
    }

    /**
     * @param \Ilch\Translator $translator
     * @param \Modules\User\Models\User|null $User
     */
    public function __construct(\Ilch\Translator $translator, ?\Modules\User\Models\User $User)
    {
        $this->setUser($User)
            ->setTranslator($translator);
        $gameMapper = new GameMapper();

        $config = \Ilch\Registry::get('config');
        $gameMapper->deleteByDays($config->get('hangman_Days_Old_Del') ?? 30);

        $this->setLocked(!$config->get('hangman_Guest_Allow') && !$User);

        $gameId = $gameMapper->getEntryByUserSessionIp($this->getUser());

        if ($gameId) {
            $this->setGameModel($gameMapper->getEntryById($gameId));
        }

        $this->wordToArray()
            ->checkWin()
            ->checkOver();
    }

    /**
     * @return $this
     */
    private function wordToArray(): Hangman
    {
        $this->setWordLetters([]);

        $entry = new WordsModel();
        if ($this->getGameModel()->getWordId()) {
            $wordsMapper = new WordsMapper();
            $entry = $wordsMapper->getEntryById($this->getGameModel()->getWordId());
        }
        $word = '';
        if ($entry) {
            $word = $entry->getText();
        } else {
            $gameMapper = new GameMapper();
            $gameMapper->delete($this->getGameModel()->getId());
            $this->setDBfail();
        }

        for ($i = 0; $i < strlen($word); $i++) {
            $this->addWordLetter($word[$i], $i);
        }
        return $this;
    }

    /**
     * @param \Ilch\Request $request
     * @return Hangman
     */
    public function playGame(\Ilch\Request $request): Hangman
    {
        if (!$this->getLocked() || !$this->getDBfail()) {
            if ($request->isPost()) {
                $this->setGameChange()
                    ->updateLastActivity();
            }

            //player is changing the game difficulty
            if ($request->getPost('change')) {
                $this->changeDifficulty($request->getPost('difficulty', 0));
            }

            //player pressed the button to start a new game
            if ($request->getPost('newgame')) {
                $this->newGame();
            }

            //player is trying to guess a letter
            if (!$this->isOver() && $request->getPost('letter')) {
                $this->guessLetter($request->getPost('letter', ''));
            }
        }
        return $this;
    }

    /**
     * @return $this
     */
    private function guessLetter(string $letter): Hangman
    {
        //only allow lowercase letters
        $letter = strtolower($letter);

        if ($this->isOver() || strlen($letter) != 1 || !$this->isLetter($letter) || in_array($letter, explode(',', $this->getGameModel()->getLetters()) ?? [])) {
            return $this;
        }

        //if the word contains this letter
        if (in_array($letter, $this->getWordLetters()) || in_array(strtoupper($letter), $this->getWordLetters())) {
            $multiplier = $this->getGameModel()->getDifficulty();

            //increase their score based on how many guesses they've used so far
            if ($this->getGameModel()->getHealth() > (100 / ceil($this->getGuesses() / 5))) {
                $this->getGameModel()->addScore((5 * $multiplier));
            } else if ($this->getGameModel()->getHealth() > (100 / ceil($this->getGuesses() / 4))) {
                $this->getGameModel()->addScore((4 * $multiplier));
            } else if ($this->getGameModel()->getHealth() > (100 / ceil($this->getGuesses() / 3))) {
                $this->getGameModel()->addScore((3 * $multiplier));
            } else if ($this->getGameModel()->getHealth() > (100 / ceil($this->getGuesses() / 2))) {
                $this->getGameModel()->addScore((2 * $multiplier));
            } else {
                $this->getGameModel()->addScore((1 * $multiplier));
            }
        } else {//word doesn't contain the letter
            //reduce their health
            $this->getGameModel()->addHealth(ceil(100 / $this->getGuesses()) * -1);
        }

        //add the letter to the letters array
        $this->getGameModel()->addLetter($letter);

        $this->checkWin()
            ->checkOver();

        if (($this->getWon() || $this->getOver() ) && $this->getGameModel()->getUserId()) {
            $highscoreMapper = new HighscoreMapper();

            $highscoreModel = $highscoreMapper->getEntryByUserId($this->getGameModel()->getUserId());
            if (!$highscoreModel) {
                $highscoreModel = new HighscoreModel();
            }
            $highscoreModel->setUserId($this->getGameModel()->getUserId())
                ->addScore($this->getGameModel()->getScore())
                ->addGames();

            $highscoreMapper->save($highscoreModel);
        }

        $gameMapper = new GameMapper();
        $gameMapper->save($this->getGameModel());
        return $this;
    }

    /**
     * @return $this
     */
    private function checkWin(): Hangman
    {
        $found = 0;
        for ($i = 0; $i <= $this->getCountWordLetters()-1; $i++) {
            foreach (explode(',', $this->getGameModel()->getLetters()) ?? [] as $letter) {
                if ($letter == strtolower($this->getWordLetter($i))) {
                    $found++;
                }
            }

            if (!$this->isLetter($this->getWordLetter($i))) {
                $found++;
            }
        }

        if ($this->getCountWordLetters() == $found) {
            $this->setWon();
        }
        return $this;
    }

    /**
     * @return $this
     */
    private function checkOver(): Hangman
    {
        $notFound = 0;
        foreach(explode(',', $this->getGameModel()->getLetters()) ?? [] as $letter) {
            if (!in_array($letter, $this->getWordLetters() ?? [])) {
                $notFound++;
            }
        }

        if ($notFound >= $this->getGuesses()) {
            $this->setOver();
        }
        return $this;
    }

    /**
     * @return $this
     */
    private function newGame(): Hangman
    {
        //Set up the game
        $gameMapper = new GameMapper();
        $this->getGameModel()
            ->setLetters('')
            ->setHealth(100)
            ->setScore(0)
            ->setSessionId(session_id());

        if ($this->getUser()) {
            $this->getGameModel()->setUserId($this->getUser()->getId());
        }

        //pick a word for them to try and guess
        $this->setNewWord();

        if ($this->getGameModel()->getWordId()) {
            $id = $gameMapper->save($this->getGameModel());
            $this->getGameModel()->setId($id);
            $gameMapper->setGameCookie(session_id());
        }
        return $this;
    }

    /**
     * @return Hangman
     */
    private function updateLastActivity(): Hangman
    {
        $date = new \Ilch\Date();
        $this->getGameModel()->setLastActivity($date->format("Y-m-d H:i:s", true));
        return $this;
    }

    /**
     * @param int $difficulty
     * @return Hangman
     */
    private function changeDifficulty(int $difficulty = 1): Hangman
    {
        if ($this->getdifficultyType($difficulty)) {
            if ($this->getGameModel()->getDifficulty() != $difficulty) {
                $this->getGameModel()->setDifficulty($difficulty);
                $this->newGame();
            }
        }
        return $this;
    }

    /**
     * @param bool $print
     * @return $this
     */
    private function setNewWord(): Hangman
    {
        $wordsMapper = new WordsMapper();
        $entry = $wordsMapper->getRandomEntry($this->getGameModel()->getDifficulty(), $this->getTranslator()->getLocale());

        if ($entry) {
            $this->getGameModel()->setWordId($entry->getId());
        }
        return $this;
    }

    /**
     * @param bool $print
     * @return String
     */
    public function displayGame(bool $print = true): string
    {
        if ($this->getDBfail()) {
            return '';
        }
        if ($this->getLocked()) {
            return $this->getTranslator()->trans('loginfirst');
        }
        $return = '<div class="form-group">
            <div class="col-xs-6 grid">';
        if (!$this->isOver()) { //while the game isn't over
            $return .= '        <div class="col-xs-12 row" id="picture">'.$this->picture().'</div>
                    <div class="col-xs-12 row" id="guess_word">' . $this->solvedWord() . '</div>
                </div>
                <div class="col-xs-6 grid">
                '.$this->displayletter();

            if (!empty($this->getGameModel()->getLetters())) {
                $return .= '        <div id="guessed_letters" class="form-group col-lg-12 row">'.$this->getTranslator()->trans('lettersGuessed').': '.str_replace(',', ", ", $this->getGameModel()->getLetters()).'</div>';
            }

            //display the difficulty dropdown box
            $return .= $this->displaydifficulty();
        } else {
            if ($this->getGameModel()->getId()) {
                //they've won the game
                if ($this->getWon()) {
                    $return .= $this->successMsg($this->getTranslator()->trans('gameWinMsg', $this->getGameModel()->getScore()));
                } else if ($this->getGameModel()->getHealth() <= 0 || $this->getOver()) {
                    $return .= $this->errorMsg($this->getTranslator()->trans('gameLosMsg', $this->getGameModel()->getScore())). '
                        <div class="col-xs-12 row" id="picture">' . $this->picture($this->getGuesses()) . '</div>';
                }
                $return .= '<div class="col-xs-12 row" id="guess_word">' . $this->solvedWord() . '</div>';
            }
            $return .= '    </div>
                <div class="col-xs-6">';
        }
        $return .= '    </div>
            <div class="col-xs-12">
                <div><input type="submit" class="btn btn-default pull-right" name="newgame" value="'.$this->getTranslator()->trans('newGame').'" /></div>
            </div>
        </div>';

        if ($print) {
            echo $return;
        }
        return $return;
    }

    /**
     * @return String
     */
    private function displayletter(): string
    {
        $config = \Ilch\Registry::get('config');
        if ($config->get('hangman_Letter_Btn') ?? false) {
            $div = 5;
            $rows = count($this->alphabet) / $div;
            $return = '<div class="form-group row">
<div class="form-group">
<label for="letter_div" class="col-lg-2 control-label">
    ' . $this->getTranslator()->trans('letter') . '
</label>
<div class="col-lg-10" id="letter_div" name="letter_div">
<div  class="btn-group btn-group-sm" role="group" aria-label="Basic example">';
for ($i = 0; $i < $rows; $i++) {
    $return .= '<div class="btn-group col-xs-12">' . PHP_EOL;
    for ($ii = 0; $ii < $div; $ii++) {
        $id = $i * $div + $ii;
        if (isset($this->alphabet[$id])) {
            $letter = $this->alphabet[$id];
            $return .= '<button type="submit" class="btn btn-default" id="letter" name="letter" value="' . $letter . '"'.(in_array($letter, explode(',', $this->getGameModel()->getLetters()) ?? []) ? ' disabled' : '').'>' . strtoupper($letter) . '</button>' . PHP_EOL;
        }
    }
    $return .= '</div>' . PHP_EOL;
}
$return .= '</div>
</div>
</div>
</div>';
            return $return;
        } else {
            return '<div class="form-group row">
                    <div class="form-group">
                        <label for="letter" class="col-lg-2 control-label">
                            ' . $this->getTranslator()->trans('letter') . '
                        </label>
                        <div class="col-lg-4">
                            <input class="form-control"
                                type="text"
                                id="letter"
                                name="letter"
                                size="2"
                                maxlength="1"
                                value="" />
                        </div>
                        <div class="col-lg-2"><input type="submit" class="btn btn-default pull-right" name="submit" id="submit" value="' . $this->getTranslator()->trans('guess') . '" /></div>
                        <div class="col-lg-4">&nbsp;</div>
                    </div>
                </div>';
        }
    }

    /**
     * @return String
     */
    private function displaydifficulty(): string
    {
        $return = '<div class="form-group row">
                        <div class="form-group">
                            <label for="difficulty" class="col-lg-2 control-label">
                                '.$this->getTranslator()->trans('difficulty').'
                            </label>
                            <div class="col-lg-4">
                                <select name="difficulty" id="difficulty" class="form-control" />';
        foreach($this->getDifficultyTypes() ?? [] as $id => $name) {
            $return .= '<option value="'.$id.'"'.($this->getGameModel()->getDifficulty() == $id ? '" selected="selected"' : '').'>'.$this->getTranslator()->trans($name).'</option>';
        }
        $return .= '</select>
                </div>
                <div class="col-lg-2"><input type="submit" class="btn btn-default pull-right" name="change" id="change" value="'.$this->getTranslator()->trans('change').'" /></div>
                <div class="col-lg-4">&nbsp;</div>
            </div>
        </div>';
        return $return;
    }

    /**
     * @return bool
     */
    private function isOver(): bool
    {
        if (!$this->getGameModel()->getId()) {
            return true;
        }

        if ($this->getWon()) {
            return true;
        }

        if ($this->getOver()) {
            return true;
        }

        if ($this->getGameModel()->getHealth() < 0) {
            return true;
        }

        return false;
    }

    /**
     * @param int $count
     * @return String
     */
    public function picture(int $count = 0): string
    {
        if (!$count) {
            for ($i = 100; $i >= 0; $i -= ceil(100 / $this->getGuesses())) {
                if ($this->getGameModel()->getHealth() == $i) {
                    break;
                }
                $count++;
            }
        }

        if (file_exists(APPLICATION_PATH.'/modules/hangman/static/img/'.$count.'.jpg')) {
            return '<img src="'.BASE_URL.'/application/modules/hangman/static/img/'.$count.'.jpg" alt="'.$this->getTranslator()->trans('hangman').'" title="'.$this->getTranslator()->trans('hangman').'">';
        } else {
            return 'ERROR: '.$count.'.jpg is missing from the hangman images folder';
        }
    }

    /**
     * @param string $msg
     * @return String
     */
    public function errorMsg(string $msg): string
    {
        return $this->msg($msg, 'danger');
    }

    /**
     * @param string $msg
     * @return String
     */
    public function successMsg(string $msg): string
    {
        return $this->msg($msg);
    }

    /**
     * @param string $msg
     * @param string $type
     * @return String
     */
    private function msg(string $msg, string $type = 'success'): string
    {
        return '<div class="alert alert-'.$type.' alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'.$msg.'</div>';
    }

    /**
     * @return String
     */
    private function solvedWord(): string
    {
        $result = "";

        for ($i = 0; $i <= $this->getCountWordLetters()-1; $i++) {
            $found = false;

            foreach(explode(',', $this->getGameModel()->getLetters()) ?? [] as $letter) {
                if ($letter == strtolower($this->getWordLetter($i))) {
                    $result .= $this->getWordLetter($i); //they've guessed this letter
                    $found = true;
                }
            }

            if (!$found && $this->isLetter($this->getWordLetter($i))) {
                $result .= "&nbsp;_&nbsp;"; //they haven't guessed this letter
            } else if (!$found) { //this is a space or non-alpha character
                //make spaces more noticable
                if ($this->getWordLetter($i) == " ") {
                    $result .= "&nbsp;&nbsp;&nbsp;";
                } else {
                    $result .= $this->getWordLetter($i);
                }
            }
        }
        return $result;
    }

    /**
     * @param string $value
     * @return bool
     */
    private function isLetter(string $value): bool
    {
        if (in_array(strtolower($value), $this->alphabet)) {
            return true;
        }
        return false;
    }

    /**
     * @return String
     */
    public function gettext(): string
    {
        $configClass = '\\Modules\\'.ucfirst('Hangman').'\\Config\\Config';
        $config = new $configClass();
        return " -> &copy; by Dennis Reilard alias hhunderter (Version: ".$config->config['version'].")";
    }
}
