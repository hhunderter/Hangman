<?php
/**
 * @copyright Dennis Reilard alias hhunderter
 * @package ilch
 */

namespace Modules\Hangman\Config;

use Modules\Hangman\Config\HangmanData as HangmanData;

class Config extends \Ilch\Config\Install
{
    /**
     * @var array
     */
    public $config = [
        'key' => 'hangman',
        'version' => '1.0.0',
        'icon_small' => 'fa-list-ol',
        'author' => 'Reilard, Dennis alias hhunderter',
        'link' => 'https://github.com/hhunderter/hangman',
        'official' => false,
        'languages' => [
            'de_DE' => [
                'name' => 'Galgenmännchen',
                'description' => 'Ein kleines Galgenmännchen Spiel',
            ],
            'en_EN' => [
                'name' => 'Hangman',
                'description' => 'A little hangman game',
            ],
        ],
        'ilchCore' => '2.1.43',
        'phpVersion' => '7.4',
    ];

    public function install()
    {
        $databaseConfig = new \Ilch\Config\Database($this->db());
        $databaseConfig->set('hangman_Guest_Allow', '0')
            ->set('hangman_Days_Old_Del', '30')
            ->set('hangman_Letter_Btn', '1');
        
        $this->db()->queryMulti($this->getInstallSql());

        $hangmanData = new HangmanData();
        $hangmanData->getFillData();
    }

    public function uninstall()
    {
        $databaseConfig = new \Ilch\Config\Database($this->db());
        $databaseConfig->delete('hangman_Guest_Allow')
            ->delete('hangman_Days_Old_Del')
            ->delete('hangman_Letter_Btn');

        $this->db()->drop('hangman_words', true);
        $this->db()->drop('hangman_game', true);
        $this->db()->drop('hangman_highscore', true);
    }

    /**
     * @return string
     */
    public function getInstallSql(): string
    {
        return '
            CREATE TABLE IF NOT EXISTS `[prefix]_hangman_words` (
              `id` INT(11) NOT NULL AUTO_INCREMENT,
              `text` VARCHAR(255) NOT NULL,
              `difficulty` tinyint(1) NOT NULL,
              `locale` VARCHAR(255) NOT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

            CREATE TABLE IF NOT EXISTS `[prefix]_hangman_game` (
              `id` INT(11) NOT NULL AUTO_INCREMENT,
              `user_id` INT(11) NOT NULL,
              `session_id` VARCHAR(255) NOT NULL DEFAULT \'\',
              `last_activity` DATETIME NOT NULL,
              `score` INT(11) NOT NULL,
              `health` INT(11) NOT NULL,
              `word_id` INT(11) NOT NULL,
              `difficulty` tinyint(1) NOT NULL,
              `letters` VARCHAR(255) NOT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

            CREATE TABLE IF NOT EXISTS `[prefix]_hangman_highscore` (
              `id` INT(11) NOT NULL AUTO_INCREMENT,
              `user_id` INT(11) UNSIGNED NOT NULL,
              `score` INT(11) NOT NULL,
              `games` INT(11) NOT NULL,
              PRIMARY KEY (`id`),
              INDEX `FK_[prefix]_hangman_highscore_[prefix]_users` (`user_id`) USING BTREE,
              CONSTRAINT `FK_[prefix]_hangman_highscore_[prefix]_users` FOREIGN KEY (`user_id`) REFERENCES `[prefix]_users`(`id`) ON UPDATE NO ACTION ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

        ';
    }

    /**
     * @param string $installedVersion
     * @return string
     */
    public function getUpdate(string $installedVersion): string
    {
        switch ($installedVersion) {
            case "1.0.0":
            // update zu 1.?.?
                /*
                */

        }
        return 'Update function executed.';
    }
}
