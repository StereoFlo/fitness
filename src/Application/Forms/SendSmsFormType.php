<?php

namespace Application\Forms;

use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Domain\Training\Entity\TrainingUser;

/**
 * Class SendSmsFormType
 * @package Application\Forms
 */
class SendSmsFormType extends AbstractSendMessageFormType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type', HiddenType::class, ['data' => TrainingUser::TYPE_SMS]);
        parent::buildForm($builder, $options);

    }
}
