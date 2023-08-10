<?php

/**
 * @copyright Dennis Reilard alias hhunderter
 * @package ilch
 */

namespace Modules\Hangman\Controllers\Admin;

use Ilch\Validation;

class Settings extends \Ilch\Controller\Admin
{
    public function init()
    {
        $items = [
            [
                'name' => 'manage',
                'active' => false,
                'icon' => 'fa-solid fa-table-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'index'])
            ],
            [
                'name' => 'reset',
                'active' => false,
                'icon' => 'fa-solid fa-trash-can',
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'reset'])
            ],
            [
                'name' => 'settings',
                'active' => true,
                'icon' => 'fa-solid fa-gears',
                'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'index'])
            ]
        ];

        $this->getLayout()->addMenu(
            'hangman',
            $items
        );
    }

    public function indexAction()
    {
        $this->getLayout()->getAdminHmenu()
            ->add($this->getTranslator()->trans('hangman'), ['controller' => 'index', 'action' => 'index'])
            ->add($this->getTranslator()->trans('settings'), ['controller' => 'settings', 'action' => 'index']);

        if ($this->getRequest()->isPost()) {
            $validation = Validation::create($this->getRequest()->getPost(), [
                'Guest_Allow' => 'required|numeric|min:0|max:1',
                'Days_Old_Del' => 'required|numeric|integer',
                'Letter_Btn' => 'required|numeric|min:0|max:1',
            ]);

            if ($validation->isValid()) {
                $this->getConfig()->set('hangman_Guest_Allow', $this->getRequest()->getPost('Guest_Allow'));
                $this->getConfig()->set('hangman_Days_Old_Del', $this->getRequest()->getPost('Days_Old_Del'));
                $this->getConfig()->set('hangman_Letter_Btn', $this->getRequest()->getPost('Letter_Btn'));
                $this->getConfig()->set('hangman_Color', $this->getRequest()->getPost('Color'));

                $this->redirect()
                    ->withMessage('saveSuccess')
                    ->to(['action' => 'index']);
            }
            $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
            $this->redirect()
                ->withInput()
                ->withErrors($validation->getErrorBag())
                ->to(['action' => 'index']);
        }

        $this->getView()->set('Guest_Allow', $this->getConfig()->get('hangman_Guest_Allow'));
        $this->getView()->set('Days_Old_Del', $this->getConfig()->get('hangman_Days_Old_Del'));
        $this->getView()->set('Letter_Btn', $this->getConfig()->get('hangman_Letter_Btn'));
        $this->getView()->set('Color', $this->getConfig()->get('hangman_Color'));
    }
}
