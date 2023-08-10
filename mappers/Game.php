<?php

/**
 * @copyright Dennis Reilard alias hhunderter
 * @package ilch
 */

namespace Modules\Hangman\Mappers;

use Modules\Hangman\Models\Game as EntriesModel;

class Game extends \Ilch\Mapper
{
    /**
     * @var string
     */
    public $tablename = 'hangman_game';

    /**
     * @return boolean
     */
    public function checkDB(): bool
    {
        return $this->db()->ifTableExists($this->tablename);
    }

    /**
     * @param array $where
     * @param array $orderBy
     * @param \Ilch\Pagination|null $pagination
     * @return array|null
     */
    public function getEntriesBy(array $where = [], array $orderBy = ['a.id' => 'DESC'], ?\Ilch\Pagination $pagination = null): ?array
    {
        $select = $this->db()->select()
            ->fields(['*'])
            ->from([$this->tablename])
            ->where($where)
            ->order($orderBy);

        if ($pagination !== null) {
            $select->limit($pagination->getLimit())
                ->useFoundRows();
            $result = $select->execute();
            $pagination->setRows($result->getFoundRows());
        } else {
            $result = $select->execute();
        }

        $entriesArray = $result->fetchRows();
        if (empty($entriesArray)) {
            return null;
        }
        $entries = [];

        foreach ($entriesArray as $entryArray) {
            $entryModel = new EntriesModel();

            $entryModel->setByArray($entryArray);

            $entries[] = $entryModel;
        }
        return $entries;
    }

    /**
     * @param array $where
     * @param \Ilch\Pagination|null $pagination
     * @return array|null
     */
    public function getEntries(array $where = [], ?\Ilch\Pagination $pagination = null): ?array
    {
        return $this->getEntriesBy($where, ['id' => 'ASC'], $pagination);
    }

    /**
     * @param \Ilch\Pagination|null $pagination
     * @return null|array
     */
    public function getList(?\Ilch\Pagination $pagination = null): ?array
    {
        return $this->getEntriesBy([], ['id' => 'ASC'], $pagination);
    }

    /**
     * @param int|EntriesModel $id
     * @return null|EntriesModel
     */
    public function getEntryById(int $id): ?EntriesModel
    {
        if (is_a($id, EntriesModel::class)) {
            $id = $id->getId();
        }

        $entries = $this->getEntriesBy(['id' => (int)$id], []);

        if (!empty($entries)) {
            return reset($entries);
        }

        return null;
    }

    /**
     * @param \Modules\User\Models\User|null $User
     * @return integer
     */
    public function getEntryByUserSessionIp(?\Modules\User\Models\User $User = null): int
    {
        $User_Id = 0;
        if ($User) {
            $User_Id = $User->getId();
        }

        $oldsession = session_id();
        if ($oldsession == $this->getGameCookie()) {
            if ($oldsession != session_id()) {
                $this->setGameCookie(session_id());
            }
        }

        $gameId = (int) $this->db()->select('id')
            ->from($this->tablename)
            ->Where(['session_id' => $oldsession])
            ->execute()
            ->fetchCell();
        if (!$gameId && $User_Id > 0) {
            $gameId = (int) $this->db()->select('id')
                ->from($this->tablename)
                ->Where(['user_id >' => 0, 'user_id' => $User_Id])
                ->execute()
                ->fetchCell();
        }

        return $gameId;
    }

    /**
     * @param String $sessionid
     * @return $this
     */
    public function setGameCookie(string $sessionid): Game
    {
        setcookieIlch('hangman_game', $sessionid, strtotime('+1 days'));
        return $this;
    }

    /**
     * @return string|null
     */
    public function getGameCookie(): ?string
    {
        return $_COOKIE['hangman_game'] ?? null;
    }

    /**
     * @param EntriesModel $model
     * @return integer
     */
    public function save(EntriesModel $model): int
    {
        $fields = $model->getArray();

        if ($model->getId()) {
            $this->db()->update($this->tablename)
                ->values($fields)
                ->where(['id' => $model->getId()])
                ->execute();
            $result = $model->getId();
        } else {
            $result = $this->db()->insert($this->tablename)
                ->values($fields)
                ->execute();
        }

        return $result;
    }

    /**
     * @param int|EntriesModel $id
     * @return boolean
     */
    public function delete($id): bool
    {
        if (is_a($id, EntriesModel::class)) {
            $id = $id->getId();
        }

        return $this->db()->delete($this->tablename)
            ->where(['id' => (int)$id])
            ->execute();
    }

    /**
     * @param int $days
     * @return boolean
     */
    public function deleteByDays(int $days): bool
    {
        $date = new \Ilch\Date();
        $date->modify('-' . $days . ' Days');

        return $this->db()->delete($this->tablename)
            ->where(['last_activity <' => $date->format("Y-m-d H:i:s", true)])
            ->execute();
    }

    /**
     * @param int $options
     * @return string
     */
    public function getJson(int $options = 0): string
    {
        $entriesArray = $this->getEntriesBy();
        $entries = [];

        if ($entriesArray) {
            foreach ($entriesArray as $entryModel) {
                $entries[] = $entryModel->getArray(false);
            }
        }

        return json_encode($entries, $options);
    }
}
