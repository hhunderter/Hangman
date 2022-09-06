<?php
/**
 * @copyright Dennis Reilard alias hhunderter
 * @package ilch
 */

namespace Modules\Hangman\Controllers;

use Modules\Hangman\libs\Hangman as HangmanLib;
use Modules\User\Mappers\User as UserMapper;
use Modules\Hangman\Mappers\Highscore as HighscoreMapper;

class Index extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {
        $hangmanLib = new HangmanLib($this->getTranslator(), $this->getUser());

        $this->getLayout()->getTitle()
            ->add($this->getTranslator()->trans('hangman'));
        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('hangman'), ['controller' => 'index', 'action' => 'index']);

        $this->getView()->set('hangmanLib', $hangmanLib->playGame($this->getRequest()));

        if ($hangmanLib->getGameChange()) {
            $this->redirect()
                ->to(['action' => 'index']);
        }

        $this->getView()->set('gettext', (!empty($this->getRequest()->getParam('copy'))?$hangmanLib->gettext():''));
    }

    public function highscoreAction()
    {
        $highscoreMapper = new HighscoreMapper();

        $pagination = new \Ilch\Pagination();
        $pagination->setRowsPerPage(10);
        $pagination->setPage(1);

        $this->getLayout()->getTitle()
            ->add($this->getTranslator()->trans('hangman'));
        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('hangman'), ['controller' => 'index', 'action' => 'index'])
            ->add($this->getTranslator()->trans('highscore'), ['controller' => 'index', 'action' => 'highscore']);

        $this->getView()->set('entries', $highscoreMapper->getList($pagination));
        $this->getView()->set('userMapper', (new UserMapper()));
    }
}
