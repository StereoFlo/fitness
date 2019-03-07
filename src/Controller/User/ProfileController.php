<?php

namespace Controller\User;

use Application\Exceptions\ModelNotFoundException;
use Application\Forms\ProfileFormType;
use Controller\BaseController;
use Domain\User\Model\UserModel;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ProfileController
 * @package Controller\User
 */
class ProfileController extends BaseController
{
    /**
     * @var UserModel
     */
    private $userModel;

    /**
     * ProfileController constructor.
     *
     * @param UserModel $userModel
     */
    public function __construct(UserModel $userModel)
    {
        $this->userModel = $userModel;
    }

    /**
     * Форма профиля
     *
     * @return Response
     * @throws ModelNotFoundException
     */
    public function form(): Response
    {
        $form = $this->createForm(ProfileFormType::class, $this->getUser())->handleRequest($this->request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->userModel->setId($this->getUser()->getId())
                ->setPassword($form->get('password')->getData())
                ->save();
        }
        return $this->render('user/profile/form.html.twig', ['form' => $form->createView()]);
    }
}
