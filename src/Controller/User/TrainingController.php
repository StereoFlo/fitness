<?php

namespace Controller\User;

use Application\Exceptions\ModelNotFoundException;
use Application\Forms\SubscribeFormType;
use Controller\BaseController;
use Domain\Training\Entity\TrainingUser;
use Domain\Training\Model\TrainingModel;
use Domain\Training\Model\TrainingUserModel;
use Exception;
use function in_array;
use Symfony\Component\Form\FormError;
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
     * @var TrainingUserModel
     */
    private $trainingUserModel;

    /**
     * TrainingController constructor.
     *
     * @param TrainingModel     $trainingModel
     * @param TrainingUserModel $trainingUserModel
     */
    public function __construct(TrainingModel $trainingModel, TrainingUserModel $trainingUserModel)
    {
        $this->trainingModel = $trainingModel;
        $this->trainingUserModel = $trainingUserModel;
    }

    /**
     * Тренировка по айди
     *
     * @param int $id
     *
     * @return Response
     * @throws ModelNotFoundException
     * @throws Exception
     */
    public function show(int $id): Response
    {
        $training = $this->trainingModel->setId($id)->get();
        $form = $this->createForm(SubscribeFormType::class)->handleRequest($this->request);
        if ($form->isSubmitted()) {
            if (!in_array($form->get('type')->getData(), TrainingUser::TYPE_MAP)) {
                $form->addError(new FormError('The type that you provide is wrong'));
            }
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $this->trainingUserModel
                ->setTraining($training)
                ->setUser($this->getUser())
                ->setType($form->get('type')->getData())
                ->save();
        }
        return $this->render('user/training/show.html.twig', ['training' => $training, 'form' => $form->createView()]);
    }

    /**
     * Список тренировок
     *
     * @return Response
     */
    public function getList(): Response
    {
        $list = $this->trainingModel->getList($this->getLimit(), $this->getOffset());
        return $this->render('user/training/list.html.twig', ['list' => $list]);
    }

    /**
     * Отписаться от тренировки
     *
     * @param int $id
     *
     * @return Response
     * @throws ModelNotFoundException
     */
    public function unsubscribe(int $id): Response
    {
        $training = $this->trainingModel->setId($id)->get();
        $this->trainingUserModel
            ->setTraining($training)
            ->setUser($this->getUser())
            ->remove();

        return $this->redirectToRoute('user.training.show', ['id' => $training->getId()]);
    }
}
