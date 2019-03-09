<?php

namespace Controller\Admin;

use Application\Exceptions\ModelNotFoundException;
use Application\Forms\UserEditFromType;
use Controller\BaseController;
use Domain\User\Entity\User;
use Domain\User\Model\UserModel;
use function json_encode;
use function md5;
use function mt_rand;
use OldSound\RabbitMqBundle\RabbitMq\Producer;
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
     * @var Producer
     */
    private $producer;

    /**
     * UserController constructor.
     *
     * @param UserModel $userModel
     * @param Producer  $producer
     */
    public function __construct(UserModel $userModel, Producer $producer)
    {
        $this->userModel = $userModel;
        $this->producer = $producer;
    }

    /**
     * @return Response
     */
    public function getList(): Response
    {
        return $this->render('admin/user/list.html.twig', ['user' => $this->userModel->getList($this->getLimit(), $this->getOffset())]);
    }

    /**
     * @todo move guanocode to model
     * @param int|null $id
     *
     * @return Response
     * @throws ModelNotFoundException
     */
    public function form(?int $id): Response
    {
        $user = $id ? $this->userModel->setId($id)->get() : User::create();
        $form = $this->createForm(UserEditFromType::class, $user)->handleRequest($this->request);
        if ($form->isSubmitted()) {
            $userByEmail = $this->userModel->setEmail($form->get('email')->getData())->getByEmail(true);
            if ($user && $userByEmail && $user->getId() !== $userByEmail->getId()) {
                $form->addError(new FormError('email is exits'));
            }
            $userByPhone = $this->userModel->setPhone($form->get('phone')->getData())->getByPhone(true);
            if ($user && $userByPhone && $user->getId() !== $userByPhone->getId()) {
                $form->addError(new FormError('phone is exits'));
            }
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->userModel
                ->setPhone($form->get('phone')->getData())
                ->setEmail($form->get('email')->getData())
                ->setBirthDate($form->get('birthDate')->getData())
                ->setRole($form->get('role')->getData())
                ->setName($form->get('name')->getData())
                ->setSex($form->get('sex')->getData())
                ->setActivateCode(md5(mt_rand(0, 9999)))
                ->setPhoto($form->get('photo')->getData())
                ->setIsBlocked($form->get('isBlocked')->getData())
                ->save();
            $this->producer->setContentType('application/json')
                ->publish(json_encode(['to' => $user->getEmail(), 'code' => $user->getActivateCode(), 'type' => 'register']));
            return $this->redirectToRoute('admin.user.list');
        }
        return $this->render('admin/user/edit.html.twig', ['form' => $form->createView()]);
    }
}
