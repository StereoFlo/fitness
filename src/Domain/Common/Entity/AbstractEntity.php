<?php

namespace Domain\Common\Entity;

use DateTime;
use Exception;

/**
 * Class AbstractEntity
 * @package Domain\Common\Entity
 */
abstract class AbstractEntity
{
    /**
     * @var DateTime
     */
    protected $createdAt;
    /**
     * @var DateTime
     */
    protected $updatedAt;

    /**
     * @return static
     */
    public static function create()
    {
        return new static();
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param DateTime $createdAt
     *
     * @return static
     * @throws Exception
     */
    public function setCreatedAt(DateTime $createdAt = null)
    {
        if (empty($createdAt)) {
            $this->createdAt = new DateTime();
            return $this;
        }
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param DateTime $updatedAt
     *
     * @return static
     * @throws Exception
     */
    public function setUpdatedAt(DateTime $updatedAt = null)
    {
        if (empty($updatedAt)) {
            $this->updatedAt = new DateTime();
            return $this;
        }
        $this->updatedAt = $updatedAt;
        return $this;
    }
}
