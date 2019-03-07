<?php

namespace Controller;

use Symfony\Component\HttpFoundation\Response;

/**
 * Контроллер по умолчанию
 *
 * Class DefaultController
 * @package Controller
 */
class DefaultController extends BaseController
{
    /**
     * действие по умолчанию
     *
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('default/index.html.twig');
    }
}
