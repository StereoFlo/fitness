<?php

namespace Domain\User\Repository;

use Domain\Shared\Repository\AbstractRepository;
use Domain\User\Entity\User;

/**
 * Class UserRepository
 * @package Domain\User\Repository
 */
class UserRepository extends AbstractRepository
{

    /**
     * @param User $user
     * @return User
     */
    public function save(User $user): User
    {
        return parent::saveOne($user);
    }

    /**
     * @param int $id
     * @return User|null|object
     */
    public function getById(int $id): ?User
    {
        return $this->getRepository()->find($id);
    }

    /**
     * gets an entity name
     *
     * @return string
     */
    protected function getEntityName(): string
    {
        return User::class;
    }
}
