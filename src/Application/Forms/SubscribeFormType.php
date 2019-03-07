<?php

namespace Application\Forms;

use function array_flip;
use Domain\Training\Entity\TrainingUser;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class SubscribeFormType
 * @package Application\Forms
 */
class SubscribeFormType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type', ChoiceType::class, ['choices' => array_flip(TrainingUser::TYPE_MAP)])
            ->add('submit', SubmitType::class);
    }
}
