<?php

namespace Controller\Admin;

use Application\Exceptions\ModelNotFoundException;
use Application\Forms\SendEmailFormType;
use Application\Forms\SendSmsFormType;
use Application\Forms\TrainingFormType;
use Controller\BaseController;
use Domain\Training\Entity\Training;
use Domain\Training\Entity\TrainingUser;
use Domain\Training\Model\TrainingModel;
use OldSound\RabbitMqBundle\RabbitMq\Producer;
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
     * @var Producer
     */
    private $producer;

    /**
     * TrainingController constructor.
     *
     * @param TrainingModel $trainingModel
     * @param Producer      $producer
     */
    public function __construct(TrainingModel $trainingModel, Producer $producer)
    {
        $this->trainingModel = $trainingModel;
        $this->producer = $producer;
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
        $emailForm = $this->createForm(SendEmailFormType::class)->handleRequest($this->request);
        $smsForm = $this->createForm(SendSmsFormType::class)->handleRequest($this->request);
        if ($emailForm->isSubmitted() && $emailForm->isValid()) {
            $this->producer
                ->setContentType('application/json')
                ->publish(json_encode(['to' => $training->getId(), 'message' =>$emailForm->get('message')->getData(), 'type' => TrainingUser::TYPE_EMAIL]));
        }
        if ($smsForm->isSubmitted() && $smsForm->isValid()) {
            $this->producer
                ->setContentType('application/json')
                ->publish(json_encode(['to' => $training->getId(), 'message' =>$emailForm->get('message')->getData(), 'type' => TrainingUser::TYPE_SMS]));
        }
        return $this->render('admin/training/show.html.twig', ['training' => $training, 'emailForm' => $emailForm->createView(), 'smsForm' => $smsForm->createView()]);
    }

    /**
     * @param int $id
     *
     * @return Response
     * @throws ModelNotFoundException
     */
    public function remove(int $id): Response
    {
        $this->trainingModel->setId($id)->remove();
        return $this->redirectToRoute('admin.training.list');
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
