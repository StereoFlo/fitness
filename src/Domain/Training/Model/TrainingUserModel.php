<?php

namespace Domain\Training\Model;

use Domain\Shared\Model\AbstractModel;
use Domain\Training\Entity\Training;
use Domain\Training\Entity\TrainingUser;
use Domain\Training\Repository\TrainingUserRepository;
use Domain\User\Entity\User;
use Exception;

/**
 * Class TrainingUserModel
 * @package Domain\Training\Model
 */
class TrainingUserModel extends AbstractModel
{
    /**
     * @var User
     */
    private $user;

    /**
     * @var Training
     */
    private $training;

    /**
     * @var int
     */
    private $type;

    /**
     * @var TrainingUserRepository
     */
    private $trainingUserRepo;

    /**
     * TrainingUserModel constructor.
     *
     * @param TrainingUserRepository $trainingUserRepo
     */
    public function __construct(TrainingUserRepository $trainingUserRepo)
    {
        $this->trainingUserRepo = $trainingUserRepo;
    }

    /**
     * @param User $user
     *
     * @return TrainingUserModel
     */
    public function setUser(User $user): TrainingUserModel
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @param Training $training
     *
     * @return TrainingUserModel
     */
    public function setTraining(Training $training): TrainingUserModel
    {
        $this->training = $training;
        return $this;
    }

    /**
     * @param int $type
     *
     * @return TrainingUserModel
     */
    public function setType(int $type): TrainingUserModel
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return TrainingUser
     * @throws Exception
     */
    public function save(): TrainingUser
    {
        $trainingUser = $this->get();
        $trainingUser->setUserId($this->user->getId())
            ->setUser($this->user)
            ->setSubscriptionType($this->type)
            ->setTraining($this->training)
            ->setTrainingId($this->training->getId());
        if ($this->isNew) {
            $trainingUser->setCreatedAt();
        }
        return $this->trainingUserRepo->save($trainingUser);
    }

    /**
     * @return bool
     */
    public function remove(): bool
    {
        $trainingUser = $this->get();
        if ($this->isNew) {
            return false;
        }
        return $this->trainingUserRepo->remove($trainingUser);
    }

    /**
     * @return TrainingUser
     */
    public function get(): TrainingUser
    {
        $training = $this->trainingUserRepo->getById($this->training->getId(), $this->user->getId());
        if (empty($training)) {
            $this->isNew = true;
            return TrainingUser::create();
        }
        return $training;
    }
}
