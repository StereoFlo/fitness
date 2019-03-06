<?php

namespace Application\Forms;

use Domain\User\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class UserEditFromType
 * @package Application\Forms
 */
class ProfileFormType extends UserEditFromType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, ['disabled' => true])
            ->add('birthDate', DateType::class, ['disabled' => true])
            ->add('sex', ChoiceType::class, [
                'choices' => User::SEX_MAP,
                'disabled' => true
            ])
            ->add('email', EmailType::class, ['disabled' => true])
            ->add('phone', TextType::class, ['disabled' => true])
            ->add('role', ChoiceType::class, ['choices' => User::ROLE_MAP, 'disabled' => true])
            ->add('isBlocked', CheckboxType::class, ['disabled' => true])
            ->add('isActivated', CheckboxType::class, ['disabled' => true])
            ->add('password', PasswordType::class)
            ->add('submit', SubmitType::class);
    }
}
