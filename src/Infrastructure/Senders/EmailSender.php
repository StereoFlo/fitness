<?php

namespace Infrastructure\Senders;

use Swift_Mailer;
use Swift_Message;
use Twig_Environment;
use Twig_Error_Loader;
use Twig_Error_Runtime;
use Twig_Error_Syntax;

/**
 * Class EmailSender
 * @package Infrastructure\Senders
 */
class EmailSender extends AbstractSender
{
    /**
     * @var Swift_Mailer
     */
    private $mailer;
    /**
     * @var Twig_Environment
     */
    private $twig;

    /**
     * @var string
     */
    private $subject;

    /**
     * @var string
     */
    private $code;

    /**
     * EmailSender constructor.
     *
     * @param Swift_Mailer     $mailer
     * @param Twig_Environment $twig
     */
    public function __construct(Swift_Mailer $mailer, Twig_Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    /**
     * @param string $subject
     *
     * @return EmailSender
     */
    public function setSubject(string $subject): EmailSender
    {
        $this->subject = $subject;
        return $this;
    }

    /**
     * @param string $code
     *
     * @return EmailSender
     */
    public function setCode(string $code): EmailSender
    {
        $this->code = $code;
        return $this;
    }

    /**
     * sends a message
     *
     * @return mixed
     * @throws Twig_Error_Loader
     * @throws Twig_Error_Runtime
     * @throws Twig_Error_Syntax
     */
    public function send()
    {
        $this->message = (new Swift_Message($this->subject))
            ->setTo($this->to)
            ->setBody(
                $this->twig->render(
                    'email/register.html.twig',
                    ['code' => $this->code]
                ),
                'text/html'
            );
        return $this->mailer->send($this->message);
    }
}
