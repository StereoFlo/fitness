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
     * @param string $activateCode
     *
     * @return User|null|object
     */
    public function getByActivateCode(string $activateCode): ?User
    {
        return $this->getRepository()->findOneBy(['activateCode' => $activateCode]);
    }

    /**
     * @param int $limit
     * @param int $offset
     *
     * @return array
     */
    public function getUserList(int $limit, int $offset): array
    {
        $query = $this->getQueryBuilder()
            ->select('user')
            ->from($this->getEntityName(), 'user')
            ->setFirstResult($offset)
            ->setMaxResults($limit);
        return $this->getPreparedList($query, true);
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
