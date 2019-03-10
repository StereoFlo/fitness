<?php


namespace Domain\Common\Model;

/**
 * Class AbstractModel
 * @package Domain\Common\Model
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
