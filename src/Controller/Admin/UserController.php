<?php

namespace Controller\Admin;

use Controller\BaseController;
use Domain\User\Model\UserModel;
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
}
