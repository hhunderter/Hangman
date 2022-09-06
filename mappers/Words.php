<?php
/**
 * @copyright Dennis Reilard alias hhunderter
 * @package ilch
 */

namespace Modules\Hangman\Mappers;

use Modules\Hangman\Models\Words as EntriesModel;

class Words extends \Ilch\Mapper
{
    /**
     * @var string
     */
    public $tablename = 'hangman_words';

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
    public function getEntriesBy(array $where = [], array $orderBy = ['id' => 'ASC'], ?\Ilch\Pagination $pagination = null): ?array
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

        $entryArray = $result->fetchRows();

        if (empty($entryArray)) {
            return null;
        }
        $entrys = [];

        foreach ($entryArray as $entries) {
            $entryModel = new EntriesModel();

            $entryModel->setByArray($entries);

            $entrys[] = $entryModel;
        }
        return $entrys;
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
     * @param int|null $difficulty
     * @param string|null $locale
     * @return null|array
     */
    public function getList(?\Ilch\Pagination $pagination = null, int $difficulty = null, string $locale = null): ?array
    {
        $where = [];
        if ($difficulty !== null) {
            $where['difficulty'] = $difficulty;
        }
        if ($locale !== null) {
            $where['locale'] = $locale;
        }

        return $this->getEntriesBy($where, ['locale' => 'ASC', 'difficulty' => 'ASC', 'text' => 'ASC'], $pagination);
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

        $entrys = $this->getEntriesBy(['id' => (int)$id], []);

        if (!empty($entrys)) {
            return reset($entrys);
        }

        return null;
    }

    /**
     * @param int $difficulty
     * @param string $locale
     * @return null|EntriesModel
     */
    public function getRandomEntry(int $difficulty = 1, string $locale = ''): ?EntriesModel
    {
        $pagination = new \Ilch\Pagination();
        $pagination->setRowsPerPage(1);
        $pagination->setPage(1);

        $entrys = $this->getEntriesBy(array_merge(['difficulty' => $difficulty], ($locale ? [$this->db()->select()->orX([$this->db()->select()->orX(['locale' => $locale]), $this->db()->select()->orX(['locale' => ''])])] : [])), ['RAND()' => ''], $pagination);

        if (!empty($entrys)) {
            return reset($entrys);
        }

        return null;
    }

    /**
     * @param array $where
     * @return integer
     */
    public function getCount(array $where = []): int
    {
        $this->db()->select('id')
            ->from($this->tablename)
            ->where($where)
            ->execute()
            ->fetchRows();
        $counter = $this->db()->getAffectedRows();

        if (empty($counter)) {
            return 0;
        }

        return $counter;
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
            $result = (int)$this->db()->insert($this->tablename)
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
     * @param int $options
     * @return string
     */
    public function getJson(int $options = 0): string
    {
        $entryArray = $this->getEntriesBy();
        $entrys = [];

        if ($entryArray) {
            foreach ($entryArray as $entryModel) {
                $entrys[] = $entryModel->getArray(false);
            }
        }
        
        return json_encode($entrys, $options);
    }
}
