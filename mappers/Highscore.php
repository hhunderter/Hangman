<?php

/**
 * @copyright Dennis Reilard alias hhunderter
 * @package ilch
 */

namespace Modules\Hangman\Mappers;

use Modules\Hangman\Models\Highscore as EntriesModel;

class Highscore extends \Ilch\Mapper
{
    /**
     * @var string
     */
    public $tablename = 'hangman_highscore';

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
        return $this->getEntriesBy([], ['score' => 'DESC', 'games' => 'ASC'], $pagination);
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
     * @param int|EntriesModel $userId
     * @return null|EntriesModel
     */
    public function getEntryByUserId(int $userId): ?EntriesModel
    {
        if (is_a($userId, EntriesModel::class)) {
            $userId = $userId->getUserId();
        }

        $entries = $this->getEntriesBy(['user_id' => (int)$userId], []);

        if (!empty($entries)) {
            return reset($entries);
        }

        return null;
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
     * @return boolean
     * @throws \Ilch\Database\Exception
     */
    public function reset(): bool
    {
        $this->db()->truncate($this->tablename);
        return $this->db()->queryMulti('ALTER TABLE `[prefix]_' . $this->tablename . '` auto_increment = 1;');
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
