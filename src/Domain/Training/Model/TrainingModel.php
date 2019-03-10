<?php

namespace Domain\Training\Model;

use Application\Exceptions\ModelNotFoundException;
use Domain\Common\Model\AbstractModel;
use Domain\Training\Entity\Training;
use Domain\Training\Repository\TrainingRepository;

/**
 * Class TrainingModel
 * @package Domain\Training\Model
 */
class TrainingModel extends AbstractModel
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
     * @var TrainingRepository
     */
    private $trainingRepo;

    /**
     * @var TrainingUserModel
     */
    private $trainingUserModel;

    /**
     * TrainingModel constructor.
     *
     * @param TrainingRepository $trainingRepo
     * @param TrainingUserModel  $trainingUserModel
     */
    public function __construct(TrainingRepository $trainingRepo, TrainingUserModel $trainingUserModel)
    {
        $this->trainingRepo = $trainingRepo;
        $this->trainingUserModel = $trainingUserModel;
    }

    /**
     * @param int $id
     *
     * @return TrainingModel
     */
    public function setId(int $id): TrainingModel
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param string $name
     *
     * @return TrainingModel
     */
    public function setName(string $name): TrainingModel
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param string $trainerName
     *
     * @return TrainingModel
     */
    public function setTrainerName(string $trainerName): TrainingModel
    {
        $this->trainerName = $trainerName;
        return $this;
    }

    /**
     * @param string|null $description
     *
     * @return TrainingModel
     */
    public function setDescription(?string $description): TrainingModel
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return Training
     * @throws ModelNotFoundException
     * @throws \Exception
     */
    public function save(): Training
    {
        $training = $this->get();
        if ($this->isNew) {
            $training->setCreatedAt();
        }
        $training->setName($this->name);
        $training->setTrainerName($this->trainerName);
        $training->setUpdatedAt();
        $training->setDescription($this->description);

        return $this->trainingRepo->save($training);
    }

    /**
     * @return Training
     * @throws ModelNotFoundException
     */
    public function get(): Training
    {
        if (empty($this->id)) {
            $this->isNew = true;
            return Training::create();
        }
        $training = $this->trainingRepo->getById($this->id);
        if (empty($training)) {
            throw new ModelNotFoundException('Training is not exists');
        }
        return $training;
    }

    /**
     * @return bool
     * @throws ModelNotFoundException
     */
    public function remove(): bool
    {
        $training = $this->get();
        $this->trainingUserModel->setTraining($training)->removeByTraining();
        $this->trainingRepo->remove($training);
        return true;
    }

    /**
     * @param int $limit
     * @param int $offset
     *
     * @return array
     */
    public function getList(int $limit, int $offset): array
    {
        return $this->trainingRepo->getTrainingList($limit, $offset);
    }
}
