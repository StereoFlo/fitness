<?php

namespace Controller\Admin;

use Application\Exceptions\ModelNotFoundException;
use Application\Forms\UserEditFromType;
use Controller\BaseController;
use Domain\User\Entity\User;
use Domain\User\Model\UserModel;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class UserController
 * @package Controller\Admin
 */
class UserController extends BaseController
{
    /**
     * @var UserModel
     */
    private $userModel;

    /**
     * UserController constructor.
     *
     * @param UserModel $userModel
     */
    public function __construct(UserModel $userModel)
    {
        $this->userModel = $userModel;
    }

    /**
     * @return Response
     */
    public function getList(): Response
    {
        return $this->render('admin/user/list.html.twig', ['user' => $this->userModel->getList($this->getLimit(), $this->getOffset())]);
    }

    /**
     * @param int $id
     *
     * @return Response
     * @throws ModelNotFoundException
     */
    public function edit(int $id): Response
    {
        $user = $this->userModel->setId($id)->get();
        $form = $this->createForm(UserEditFromType::class, $user)->handleRequest($this->request);
        if ($form->isSubmitted()) {
            $user = $this->userModel->setEmail($form->get('email')->getData())->getByEmail();
            if ($user) {
                $form->addError(new FormError('email is exits'));
            }
            $user = $this->userModel->setPhone($form->get('phone')->getData())->getByPhone();
            if ($user) {
                $form->addError(new FormError('phone is exits'));
            }
            if ($form->isSubmitted() && $form->isValid()) {
                $this->userModel
                    ->setPhone($form->get('phone')->getData())
                    ->setEmail($form->get('email')->getData())
                    ->setBirthDate($form->get('birthDate')->getData())
                    ->setRole(User::getRoleName($form->get('phone')->getData()))
                    ->setName($form->get('name')->getData())
                    ->setPassword($form->get('password')->getData())
                    ->setSex($form->get('sex')->getData())
                    ->save();
                return $this->redirectToRoute('admin.user.list');
            }
        }
        return $this->render('admin/user/edit.html.twig', ['form' => $form->createView()]);
    }
}
