<?php

namespace Application\Forms;

use Domain\User\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class RegisterFormType
 * @package Application\Forms
 */
class RegisterFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class)
            ->add('phone', TextType::class)
            ->add('name', TextType::class)
            ->add('birthDate', DateType::class)
            ->add('sex', ChoiceType::class, [
                'choices' => User::SEX_MAP,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Зарегистрироваться',
                'attr' => ['class' => 'btn btn-default'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
