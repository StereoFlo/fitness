<?php

namespace Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class BaseController
 * @package Controller
 */
abstract class BaseController extends AbstractController
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @param RequestStack $request
     *
     * @return BaseController
     */
    public function setRequest(RequestStack $request): BaseController
    {
        $this->request = $request->getCurrentRequest();
        return $this;
    }
}
