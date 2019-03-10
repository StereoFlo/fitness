<?php

namespace Domain\Training\Entity;

use Domain\Common\Entity\AbstractEntity;
use Domain\User\Entity\User;

/**
 * Class TrainigUser
 * @package Domain\Training\Entity
 */
class TrainingUser extends AbstractEntity
{
    const TYPE_EMAIL = 0;
    const TYPE_SMS = 1;

    const TYPE_MAP = [
        self::TYPE_EMAIL => 'Email',
        self::TYPE_SMS => 'SMS'
    ];

    /**
     * @var int
     */
    private $userId;

    /**
     * @var User
     */
    private $user;

    /**
     * @var int
     */
    private $trainingId;

    /**
     * @var Training
     */
    private $training;

    /**
     * @var int
     */
    private $subscriptionType;

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     *
     * @return TrainingUser
     */
    public function setUserId(int $userId): TrainingUser
    {
        $this->userId = $userId;
        return $this;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     *
     * @return TrainingUser
     */
    public function setUser(User $user): TrainingUser
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return int
     */
    public function getTrainingId(): int
    {
        return $this->trainingId;
    }

    /**
     * @param int $trainingId
     *
     * @return TrainingUser
     */
    public function setTrainingId(int $trainingId): TrainingUser
    {
        $this->trainingId = $trainingId;
        return $this;
    }

    /**
     * @return Training
     */
    public function getTraining(): Training
    {
        return $this->training;
    }

    /**
     * @param Training $training
     *
     * @return TrainingUser
     */
    public function setTraining(Training $training): TrainingUser
    {
        $this->training = $training;
        return $this;
    }

    /**
     * @return int
     */
    public function getSubscriptionType(): int
    {
        return $this->subscriptionType;
    }

    /**
     * @param int $subscriptionType
     *
     * @return TrainingUser
     */
    public function setSubscriptionType(int $subscriptionType): TrainingUser
    {
        $this->subscriptionType = $subscriptionType;
        return $this;
    }

    public static function getTypeName(int $type)
    {
        return isset(self::TYPE_MAP[$type]) ? self::TYPE_MAP[$type] : 'Unknown';
    }
}
