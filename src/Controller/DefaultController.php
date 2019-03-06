<?php

namespace Controller;

use Symfony\Component\HttpFoundation\Response;

/**
 * Class DefaultController
 * @package Controller
 */
class DefaultController extends BaseController
{
    /**
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('default/index.html.twig');
    }
}
