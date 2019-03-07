<?php

namespace Domain\Training\Repository;

use Domain\Shared\Repository\AbstractRepository;
use Domain\Training\Entity\TrainingUser;

/**
 * Class TrainingUserRepository
 * @package Domain\Training\Repository
 */
class TrainingUserRepository extends AbstractRepository
{
    /**
     * @param TrainingUser $trainingUser
     *
     * @return TrainingUser
     */
    public function save(TrainingUser $trainingUser): TrainingUser
    {
        return parent::saveOne($trainingUser);
    }

    /**
     * @param TrainingUser $trainingUser
     *
     * @return bool
     */
    public function remove(TrainingUser $trainingUser): bool
    {
        parent::removeOne($trainingUser);
        return true;
    }

    /**
     * @param int $trainingId
     * @param int $userId
     *
     * @return TrainingUser|null|object
     */
    public function getById(int $trainingId, int $userId): ?TrainingUser
    {
        return $this->getRepository()->findOneBy(['userId' => $userId, 'trainingId' => $trainingId]);
    }

    /**
     * gets an entity name
     *
     * @return string
     */
    protected function getEntityName(): string
    {
        return TrainingUser::class;
    }
}
