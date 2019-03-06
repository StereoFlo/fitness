<?php

namespace Domain\Training\Entity;

use Doctrine\ORM\PersistentCollection;
use Domain\Shared\Entity\AbstractEntity;
use Domain\User\Entity\User;

/**
 * Class Training
 * @package Domain\Training\Entity
 */
class Training extends AbstractEntity
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $trainerName;

    /**
     * @var string|null
     */
    private $description;

    /**
     * @var PersistentCollection|null|User[]
     */
    private $users;

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return Training
     */
    public function setId(int $id): Training
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return Training
     */
    public function setName(string $name): Training
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getTrainerName(): ?string
    {
        return $this->trainerName;
    }

    /**
     * @param string $trainerName
     *
     * @return Training
     */
    public function setTrainerName(string $trainerName): Training
    {
        $this->trainerName = $trainerName;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     *
     * @return Training
     */
    public function setDescription(?string $description): Training
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return PersistentCollection|User[]|null
     */
    public function getUsers()
    {
        return $this->users;
    }
}
