<?php

namespace Domain\Training\Repository;

use Domain\Shared\Repository\AbstractRepository;
use Domain\Training\Entity\Training;

/**
 * Class TrainingRepository
 * @package Domain\Training\Repository
 */
class TrainingRepository extends AbstractRepository
{
    /**
     * @param Training $training
     *
     * @return Training
     */
    public function save(Training $training): Training
    {
        return parent::saveOne($training);
    }

    /**
     * @param Training $training
     *
     * @return bool
     */
    public function remove(Training $training): bool
    {
        parent::removeOne($training);
        return true;
    }

    /**
     * @param int $id
     *
     * @return Training|null|object
     */
    public function getById(int $id): ?Training
    {
        return $this->getRepository()->find($id);
    }

    /**
     * @param int $limit
     * @param int $offset
     *
     * @return array
     */
    public function getTrainingList(int $limit, int $offset): array
    {
        $query = $this->getQueryBuilder()
            ->select('training')
            ->from($this->getEntityName(), 'training')
            ->setMaxResults($limit)
            ->setFirstResult($offset);
        return $this->getPreparedList($query, true);
    }

    /**
     * gets an entity name
     *
     * @return string
     */
    protected function getEntityName(): string
    {
        return Training::class;
    }
}
