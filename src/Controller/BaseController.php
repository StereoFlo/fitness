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

    /**
     * Лимит их реквеста
     *
     * @param int $defaultLimit
     *
     * @return int
     */
    protected function getLimit(int $defaultLimit = 10): int
    {
        return $this->request->query->getInt('limit', $defaultLimit);
    }

    /**
     * офсет из реквеста
     *
     * @return int
     */
    protected function getOffset(): int
    {
        return $this->request->query->getInt('offset', 0);
    }
}
