<?php

namespace Infrastructure\Consumers;

use function json_decode;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use Swift_Mailer;
use Swift_Message;
use Twig_Environment;

/**
 * Class RegisterConsumer
 * @package Infrastructure\Consumers
 */
class RegisterConsumer implements ConsumerInterface
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
     * RegisterConsumer constructor.
     *
     * @param Swift_Mailer      $mailer
     * @param Twig_Environment $twig
     */
    public function __construct(Swift_Mailer $mailer, Twig_Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    /**
     * @param AMQPMessage $msg The message
     *
     * @return mixed false to reject and requeue, any other value to acknowledge
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function execute(AMQPMessage $msg)
    {
        $response = json_decode($msg->getBody(), true);
        if ($response['type'] === 'register') {
            $message = (new Swift_Message('Register'))
                ->setTo($response['to'])
                ->setBody(
                    $this->twig->render(
                        'email/register.html.twig',
                        ['code' => $response['code']]
                    ),
                    'text/html'
                );
            $this->mailer->send($message);
        }
        return;
    }
}
