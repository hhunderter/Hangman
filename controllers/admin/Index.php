<?php
/**
 * @copyright Dennis Reilard alias hhunderter
 * @package ilch
 */

namespace Modules\Hangman\Controllers\Admin;

use Modules\Hangman\Config\HangmanData as HangmanData;

use Modules\Hangman\libs\Hangman as HangmanLib;
use Modules\Hangman\Mappers\Words as WordsMapper;
use Modules\Hangman\Models\Words as WordsModel;
use Modules\Hangman\Mappers\Highscore as HighscoreMapper;

use Ilch\Validation;

class Index extends \Ilch\Controller\Admin
{
    public function init()
    {
        $items = [
            [
                'name' => 'manage',
                'active' => false,
                'icon' => 'fas fa-th-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'index']),
                [
                    'name' => 'treat',
                    'active' => false,
                    'icon' => 'fa fa-plus-circle',
                    'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'treat'])
                ]
            ],
            [
                'name' => 'reset',
                'active' => false,
                'icon' => 'fas fa-trash-alt',
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'reset'])
            ],
            [
                'name' => 'settings',
                'active' => false,
                'icon' => 'fas fa-cogs',
                'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'index'])
            ]
        ];

        if ($this->getRequest()->getActionName() != 'reset') {
            if ($this->getRequest()->getActionName() === 'treat') {
                $items[0][0]['active'] = true;
            } else {
                $items[0]['active'] = true;
            }
        } else {
            $items[1]['active'] = true;
        }

        $this->getLayout()->addMenu(
            'hangman',
            $items
        );
    }

    public function indexAction()
    {
        $pagination = new \Ilch\Pagination();
        $pagination->setRowsPerPage($this->getConfig()->get('defaultPaginationObjects'));
        $pagination->setPage($this->getRequest()->getParam('page'));

        $hangmanLib = new HangmanLib($this->getTranslator(), null);
        $wordsMapper = new WordsMapper();

        $this->getLayout()->getAdminHmenu()
            ->add($this->getTranslator()->trans('hangman'), ['controller' => 'index', 'action' => 'index'])
            ->add($this->getTranslator()->trans('manage'), ['action' => 'index']);

        $entries = $wordsMapper->getList($pagination, (!empty($this->getRequest()->getParam('difficulty')) ? $this->getRequest()->getParam('difficulty') : null), (!empty($this->getRequest()->getParam('locale')) ? $this->getRequest()->getParam('locale') : null));

        $this->getView()->set('entries', $entries);
        $this->getView()->set('hangmanLib', $hangmanLib);
        $this->getView()->set('localeList', $this->getTranslator()->getLocaleList());

        $this->getView()->set('pagination', $pagination);
    }

    public function treatAction()
    {
        $hangmanLib = new HangmanLib($this->getTranslator(), null);
        $wordsMapper = new WordsMapper();
        $wordsModel = new WordsModel();

        $this->getLayout()->getAdminHmenu()
            ->add($this->getTranslator()->trans('hangman'), ['controller' => 'index', 'action' => 'index'])
            ->add($this->getTranslator()->trans('manage'), ['action' => 'index']);

        if ($this->getRequest()->getParam('id')) {
            $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('edit'), ['action' => 'treat']);

            $wordsModel = $wordsMapper->getEntryById($this->getRequest()->getParam('id'));
        } else {
            $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('add'), ['action' => 'treat']);
        }
        $this->getView()->set('entrie', $wordsModel);
        $this->getView()->set('hangmanLib', $hangmanLib);
        $this->getView()->set('localeList', $this->getTranslator()->getLocaleList());

        if ($this->getRequest()->isPost()) {
            $validator = [
                'difficulty'    => 'required',
                'text'          => 'required|unique:'.$wordsMapper->tablename.',text',
            ];

            if ($wordsModel->getId()) {
                $validator['text'] = 'required';
            }


            $validation = Validation::create($this->getRequest()->getPost(), $validator);

            if ($validation->isValid()) {
                $wordsModel->setLocale($this->getRequest()->getPost('locale'))
                    ->setText($this->getRequest()->getPost('text'))
                    ->setDifficulty($this->getRequest()->getPost('difficulty'));

                $wordsMapper->save($wordsModel);

                $this->redirect()
                    ->withMessage('saveSuccess')
                    ->to(['action' => 'index']);
            }
            $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
            $this->redirect()
                ->withInput()
                ->withErrors($validation->getErrorBag())
                ->to(['action' => 'treat', 'id' => $this->getRequest()->getParam('id')]);
        }
    }

    public function delAction()
    {
        $wordsMapper = new WordsMapper();
        if ($this->getRequest()->isSecure()) {
            $wordsMapper->delete($this->getRequest()->getParam('id'));

            $this->addMessage('delSuccess');
        }

        $this->redirect(['action' => 'index']);
    }

    public function exportAction()
    {
        header('Content-Type: application/json');
        $this->getLayout()->setFile('modules/hangman/layouts/iframe');
        $wordsMapper = new WordsMapper();
        $json = $wordsMapper->getJson();

        if($this->getRequest()->getParam('save')) {
            $wordsMapper->saveJsonFile($json);
        }

        echo $json;
    }

    public function importAction()
    {
        header('Content-Type: application/json');
        $this->getLayout()->setFile('modules/hangman/layouts/iframe');
        $hangmanData = new HangmanData();
        $hangmanData->setDataWords([])->wordsData(true);

        echo json_encode($hangmanData->getDataWords());
    }

    public function resetAction()
    {
        $this->getLayout()->getAdminHmenu()
            ->add($this->getTranslator()->trans('hangman'), ['action' => 'index'])
            ->add($this->getTranslator()->trans('reset'), ['action' => 'reset']);

        $highscoreMapper = new HighscoreMapper();
        if ($this->getRequest()->isSecure()) {
            $highscoreMapper->reset();

            $this->addMessage('resetSuccess');
            $this->redirect(['action' => 'index']);
        }

    }
}
