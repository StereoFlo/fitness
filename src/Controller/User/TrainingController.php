<?php

namespace Controller\User;

use Application\Exceptions\ModelNotFoundException;
use Controller\BaseController;
use Domain\Training\Model\TrainingModel;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class TrainingController
 * @package Controller\User
 */
class TrainingController extends BaseController
{
    /**
     * @var TrainingModel
     */
    private $trainingModel;

    /**
     * TrainingController constructor.
     *
     * @param TrainingModel $trainingModel
     */
    public function __construct(TrainingModel $trainingModel)
    {
        $this->trainingModel = $trainingModel;
    }

    /**
     * @param int $id
     *
     * @return Response
     * @throws ModelNotFoundException
     */
    public function show(int $id): Response
    {
        $training = $this->trainingModel->setId($id)->get();
        return $this->render('user/training/show.html.twig', ['training' => $training]);
    }

    /**
     * @return Response
     */
    public function getList(): Response
    {
        $list = $this->trainingModel->getList($this->getLimit(), $this->getOffset());
        return $this->render('user/training/list.html.twig', ['list' => $list]);
    }
}
