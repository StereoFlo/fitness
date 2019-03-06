<?php

namespace Controller\Admin;

use Application\Exceptions\ModelNotFoundException;
use Application\Forms\TrainingFormType;
use Controller\BaseController;
use Domain\Training\Entity\Training;
use Domain\Training\Model\TrainingModel;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class TrainingController
 * @package Controller\Admin
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
     * @return Response
     */
    public function getList(): Response
    {
        $list = $this->trainingModel->getList($this->getLimit(), $this->getOffset());
        return $this->render('admin/training/list.html.twig', ['list' => $list]);
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
        return $this->render('admin/training/show.html.twig', ['training' => $training]);
    }

    /**
     * @param int|null $id
     *
     * @return Response
     * @throws ModelNotFoundException
     */
    public function form(?int $id): Response
    {
        $training = $id ? $this->trainingModel->setId($id)->get() : Training::create();
        $form = $this->createForm(TrainingFormType::class, $training)->handleRequest($this->request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($training->getId()) {
                $this->trainingModel->setId($id);
            }
            $this->trainingModel->setName($form->get('name')->getData());
            $this->trainingModel->setTrainerName($form->get('trainerName')->getData());
            $this->trainingModel->setDescription($form->get('description')->getData());
            $this->trainingModel->save();
            return $this->redirectToRoute('admin.training.list');
        }
        return $this->render('admin/training/form.html.twig', ['form' => $form->createView()]);
    }
}
