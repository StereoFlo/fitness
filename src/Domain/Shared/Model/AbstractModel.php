<?php


namespace Domain\Shared\Model;

/**
 * Class AbstractModel
 * @package Domain\Shared\Model
 */
abstract class AbstractModel
{
    /**
     * @var bool
     */
    protected $isNew = false;

    /**
     * @return bool
     */
    public function isNew(): bool
    {
        return $this->isNew;
    }
}
