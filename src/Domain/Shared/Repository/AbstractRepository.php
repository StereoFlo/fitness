<?php

namespace Domain\Shared\Repository;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * Class AbstractRepository
 * @package Domain\Shared
 */
abstract class AbstractRepository
{
    /**
     * @var EntityManagerInterface
     */
    protected $manager;

    /**
     * AbstractRepository constructor.
     *
     * @param EntityManagerInterface $manager
     */
    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * gets an entity name
     *
     * @return string
     */
    abstract protected function getEntityName(): string ;

    /**
     * @param QueryBuilder $query
     * @param bool         $fetchCollection
     *
     * @return Paginator
     */
    protected function getList(QueryBuilder $query, bool $fetchCollection = false): Paginator
    {
        return new Paginator($query, $fetchCollection);
    }

    /**
     * @param QueryBuilder $query
     * @param bool         $fetchCollection
     *
     * @return array
     */
    protected function getPreparedList(QueryBuilder $query, bool $fetchCollection = false): array
    {
        $list = $this->getList($query, $fetchCollection);
        return [
            'total' => $list->count(),
            'items' => $list->getQuery()->getResult(),
        ];
    }

    /**
     * @return ObjectRepository
     */
    protected function getRepository(): ObjectRepository
    {
        return $this->manager->getRepository($this->getEntityName());
    }

    /**
     * @return QueryBuilder
     */
    protected function getQueryBuilder(): QueryBuilder
    {
        return $this->manager->createQueryBuilder();
    }

    /**
     * @param $object
     *
     * @return mixed
     */
    protected function saveOne($object)
    {
        $this->manager->persist($object);
        $this->manager->flush();

        return $object;
    }

    /**
     * @param array $objects
     *
     * @return array
     */
    protected function saveMany(array $objects): array
    {
        foreach ($objects as $object) {
            $this->manager->persist($object);
        }
        $this->manager->flush();

        return $objects;
    }

    /**
     * @param $object
     * removeOne
     */
    protected function removeOne($object): void
    {
        $this->manager->remove($object);
        $this->manager->flush();
    }

    /**
     * @param array $objects
     * removeMany
     */
    protected function removeMany(array $objects): void
    {
        foreach ($objects as $object) {
            $this->manager->remove($object);
        }
        $this->manager->flush();
    }
}
