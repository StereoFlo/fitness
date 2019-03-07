<?php

namespace Infrastructure\Consumers;

use Domain\Training\Entity\TrainingUser;
use Domain\Training\Repository\TrainingUserRepository;
use Infrastructure\Senders\EmailSender;
use Infrastructure\Senders\SmsSender;
use function json_decode;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use Twig_Error_Loader;
use Twig_Error_Runtime;
use Twig_Error_Syntax;

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
     * @var TrainingUserRepository
     */
    private $trainingUserRepo;

    /**
     * RegisterConsumer constructor.
     *
     * @param EmailSender            $emailSender
     * @param SmsSender              $smsSender
     * @param TrainingUserRepository $trainingUserRepo
     */
    public function __construct(EmailSender $emailSender, SmsSender $smsSender, TrainingUserRepository $trainingUserRepo)
    {
        $this->emailSender = $emailSender;
        $this->smsSender = $smsSender;
        $this->trainingUserRepo = $trainingUserRepo;
    }

    /**
     * @param AMQPMessage $msg The message
     *
     * @return mixed false to reject and requeue, any other value to acknowledge
     * @throws Twig_Error_Loader
     * @throws Twig_Error_Runtime
     * @throws Twig_Error_Syntax
     */
    public function execute(AMQPMessage $msg)
    {
        $response = json_decode($msg->getBody(), true);
        switch ($response['type']) {
            case 'register':
                $this->emailSender
                    ->setCode($response['code'])
                    ->setTo($response['to'])
                    ->setSubject('Register')
                    ->send();
                break;
            case TrainingUser::TYPE_EMAIL:
                $this->sendItem(TrainingUser::TYPE_EMAIL, $response);
                break;
            case TrainingUser::TYPE_SMS:
                $this->sendItem(TrainingUser::TYPE_SMS, $response);
                break;

        }
        return;
    }

    /**
     * @param int   $type
     * @param array $data
     *
     * @throws Twig_Error_Loader
     * @throws Twig_Error_Runtime
     * @throws Twig_Error_Syntax
     */
    public function sendItem(int $type, array $data): void
    {
        $sender = null;
        switch ($type) {
            case TrainingUser::TYPE_EMAIL:
                $sender = $this->emailSender;
                break;
            case TrainingUser::TYPE_SMS:
                $sender = $this->smsSender;
                break;
        }
        $trainingUserData = $this->trainingUserRepo->getUserForSend($data['to'], TrainingUser::TYPE_SMS);
        if (empty($trainingUser)) {
            return;
        }
        foreach ($trainingUserData as $trainingUser) {
            $sender
                ->setTo($trainingUser->getUser()->getPhone())
                ->setMessage($data['message'])
                ->send();
        }
        return;
    }
}
