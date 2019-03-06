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
     * @param string $email
     * @return User|null|object
     */
    public function getByEmail(string $email): ?User
    {
        return $this->getRepository()->findOneBy(['email' => $email]);
    }

    /**
     * @param string $phone
     *
     * @return User|null|object
     */
    public function getByPhone(string $phone): ?User
    {
        return $this->getRepository()->findOneBy(['phone' => $phone]);
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
