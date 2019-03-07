<?php

namespace Infrastructure\Consumers;

use Infrastructure\Senders\EmailSender;
use function json_decode;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;

/**
 * Class RegisterConsumer
 * @package Infrastructure\Consumers
 */
class SendConsumer implements ConsumerInterface
{
    /**
     * @var EmailSender
     */
    private $emailSender;

    /**
     * RegisterConsumer constructor.
     *
     * @param EmailSender $emailSender
     */
    public function __construct(EmailSender $emailSender)
    {
        $this->emailSender = $emailSender;
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
            $this->emailSender
                ->setCode($response['code'])
                ->setTo($response['to'])
                ->setSubject('Register')
                ->send();
        }
        return;
    }
}
