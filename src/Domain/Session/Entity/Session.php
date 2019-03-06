<?php


namespace Domain\Session\Entity;

/**
 * Class Session
 * @package Domain\Session\Entity
 */
class Session
{
    /**
     * @var string
     */
    private $sessId;

    /**
     * @var string
     */
    private $sessData;

    /**
     * @var int
     */
    private $sessTime;

    /**
     * @var int
     */
    private $sessLifetime;

    /**
     * @return string
     */
    public function getSessId(): string
    {
        return $this->sessId;
    }

    /**
     * @return string
     */
    public function getSessData(): string
    {
        return $this->sessData;
    }

    /**
     * @return int
     */
    public function getSessTime(): int
    {
        return $this->sessTime;
    }

    /**
     * @return int
     */
    public function getSessLifetime(): int
    {
        return $this->sessLifetime;
    }
}
