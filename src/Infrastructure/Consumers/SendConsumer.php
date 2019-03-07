<?php

namespace Infrastructure\Consumers;

use Infrastructure\Senders\EmailSender;
use Infrastructure\Senders\SmsSender;
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
     * @var SmsSender
     */
    private $smsSender;

    /**
     * RegisterConsumer constructor.
     *
     * @param EmailSender $emailSender
     * @param SmsSender   $smsSender
     */
    public function __construct(EmailSender $emailSender, SmsSender $smsSender)
    {
        $this->emailSender = $emailSender;
        $this->smsSender = $smsSender;
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
        if ($response['type'] === 'email') {
            $this->emailSender
                ->setCode($response['code'])
                ->setTo($response['to'])
                ->setSubject('Register')
                ->send();
        }
        if ($response['type'] === 'sms') {
            $this->smsSender
                ->setTo($response['to'])
                ->setMessage($response['message'])
                ->send();
        }
        return;
    }
}
