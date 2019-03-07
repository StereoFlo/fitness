<?php

namespace Application\Listeners;

use Application\Exceptions\ModelNotFoundException;
use Application\Exceptions\ValidationException;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Twig_Environment;
use Twig_Error_Loader;
use Twig_Error_Runtime;
use Twig_Error_Syntax;

/**
 * Class ExceptionListener
 * @package Shop\Application\Listeners
 */
class ExceptionListener
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var Twig_Environment
     */
    private $twig;

    /**
     * @var GetResponseForExceptionEvent
     */
    private $event;
    /**
     * @var string
     */
    private $environment;

    /**
     * ExceptionListener constructor.
     *
     * @param LoggerInterface     $logger
     * @param Twig_Environment    $twig
     * @param string              $environment
     */
    public function __construct(LoggerInterface $logger, Twig_Environment $twig, string $environment)
    {
        $this->logger = $logger;
        $this->twig = $twig;
        $this->environment = $environment;
    }

    /**
     * @param GetResponseForExceptionEvent $event
     * onKernelException
     *
     * @throws Twig_Error_Loader
     * @throws Twig_Error_Runtime
     * @throws Twig_Error_Syntax
     */
    public function onKernelException(GetResponseForExceptionEvent $event): void
    {
        if ($this->environment === 'dev') {
            return;
        }

        $this->event = $event;
        $e = $event->getException();

        switch (true) {
            case ($e instanceOf HttpException):
                $this->logger->error($e->getMessage(), $e->getTrace());
                $path = $this->event->getRequest()->getPathInfo();
                $this->event->setResponse(RedirectResponse::create('/login?redirect=' . $path));
                break;
            case ($e instanceOf ModelNotFoundException):
                $this->logger->error($e->getMessage(), $e->getTrace());
                $event->setResponse($this->getResponse(['error' => 'page not found'], 'shared/error.html.twig', $e->getCode()));
                break;
            case ($e instanceOf ValidationException):
                $this->logger->error($e->getMessage(), $e->getTrace());
                $event->setResponse($this->getResponse(['error' => $e->getMessage()], 'shared/error.html.twig', $e->getCode()));
                break;
            default:
                $this->logger->error($e->getMessage(), $e->getTrace());
                $event->setResponse($this->getResponse(['error' => 'Something was wrong'], 'shared/error.html.twig', 500));
                break;
        }
    }

    /**
     * @param array  $data
     * @param string $templateName
     * @param int    $status
     *
     * @return Response
     * @throws Twig_Error_Loader
     * @throws Twig_Error_Runtime
     * @throws Twig_Error_Syntax
     */
    private function getResponse(array $data, string $templateName, int $status = 200): Response
    {
        return new Response($this->twig->render($templateName, $data), $status);
    }
}
