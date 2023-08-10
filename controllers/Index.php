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

        $this->getView()->set('gettext', (!empty($this->getRequest()->getParam('copy')) ? $hangmanLib->gettext() : ''));
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

    public function imgAction()
    {
        header("Content-Type: image/png");
        $this->getLayout()->setFile('modules/hangman/layouts/iframe');

        $img = @imagecreatetruecolor(276, 346);

        imagealphablending($img, false);
        $transparency = imagecolorallocatealpha($img, 0, 0, 0, 127);
        imagefill($img, 0, 0, $transparency);
        imagesavealpha($img, true);

        $config = \Ilch\Registry::get('config');
        $rgbArray = $this->hex2RGB($config->get('hangman_Color') ?? '000000');
        if (is_array($rgbArray)) {
            $black = imagecolorallocate($img, $rgbArray['red'], $rgbArray['green'], $rgbArray['blue']);
        } else {
            $black = imagecolorallocate($img, 0, 0, 0);
        }

        //Galgen
        imageline($img, 72.5, 310, 250, 310, $black);
        imageline($img, 180, 310, 200, 55, $black);
        imageline($img, 72.5, 40, 200, 55, $black);
        imageline($img, 72.5, 40, 72.5, 59, $black);

        switch ($this->getRequest()->getParam('id')  ?? 0) {
            default:
            case 6:
                //Gesicht
                imageline($img, 72.5 - 13, 72.5 + 2, 72.5 - 5, 59 + 61 - 30, $black);
                imageline($img, 72.5 - 5, 72.5 + 2, 72.5 - 10, 59 + 61 - 30, $black);

                imageline($img, 72.5 - 13 + 20, 72.5 + 2, 72.5 - 5 + 20, 59 + 61 - 30, $black);
                imageline($img, 72.5 - 5 + 20, 72.5 + 2, 72.5 - 10 + 20, 59 + 61 - 30, $black);

                imageline($img, 72.5 + 15, 72.5 + 28, 72.5 - 15, 72.5 + 28, $black);

                imageline($img, 72.5 + 3 - 5, 72.5 + 28 + 4, 72.5 + 3 - 5, 72.5 + 28, $black);
                imageline($img, 72.5 + 3 + 5, 72.5 + 28 + 4, 72.5 + 3 + 5, 72.5 + 28, $black);
                imageline($img, 72.5 + 2, 72.5 + 28 + 4 + 4, 72.5 + 3, 72.5 + 28 + 2, $black);
                imagearc($img, 72.5 + 3, 72.5 + 28 + 4, 10, 10, 0, 180, $black);

                //Arm Links
                imageline($img, 72.5, 150, 72.5 - 50, 120, $black);
            // no break
            case 5:
                //Arm Rechts
                imageline($img, 72.5, 150, 72.5 + 60, 120, $black);
            // no break
            case 4:
                //Bein Rechts
                imageline($img, 72.5, 200, 72.5 + 40, 280, $black);
            // no break
            case 3:
                //Bein Links
                imageline($img, 72.5, 200, 72.5 - 40, 280, $black);
            // no break
            case 2:
                //Körper
                imageline($img, 72.5, 200, 72.5, 59 + 61, $black);
            // no break
            case 1:
                //Kopf
                imageellipse($img, 72.5, 59 + 61 / 2, 61, 61, $black);
            // no break
            case 0:
        }

        imagepng($img);
        imagedestroy($img);
    }

    /**
     * @param string $hexStr
     * @param bool $returnAsString
     * @param string $seperator
     * @return array|string|bool
     */
    private function hex2RGB(string $hexStr, bool $returnAsString = false, string $seperator = ',')
    {
        $hexStr = preg_replace('/[^0-9A-Fa-f]/', '', $hexStr); // Gets a proper hex string
        $rgbArray = array();
        if (strlen($hexStr) == 6 || strlen($hexStr) == 7) { //If a proper hex code, convert using bitwise operation. No overhead... faster
            if (strlen($hexStr) == 7) {
                $hexStr = substr($hexStr, 1);
            }
            $colorVal = hexdec($hexStr);
            $rgbArray['red'] = 0xFF & ($colorVal >> 0x10);
            $rgbArray['green'] = 0xFF & ($colorVal >> 0x8);
            $rgbArray['blue'] = 0xFF & $colorVal;
        } elseif (strlen($hexStr) == 3) { //if shorthand notation, need some string manipulations
            $rgbArray['red'] = hexdec(str_repeat(substr($hexStr, 0, 1), 2));
            $rgbArray['green'] = hexdec(str_repeat(substr($hexStr, 1, 1), 2));
            $rgbArray['blue'] = hexdec(str_repeat(substr($hexStr, 2, 1), 2));
        } else {
            return false; //Invalid hex color code
        }
        return $returnAsString ? implode($seperator, $rgbArray) : $rgbArray; // returns the rgb string or the associative array
    }
}
