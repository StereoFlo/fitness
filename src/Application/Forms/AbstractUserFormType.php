<?php

namespace Application\Forms;

use Domain\User\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class AbstractUserFormType
 * @package Application\Forms
 */
abstract class AbstractUserFormType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('birthDate', DateType::class)
            ->add('sex', ChoiceType::class, [
                'choices' => User::SEX_MAP,
            ])
            ->add('email', EmailType::class)
            ->add('phone', TextType::class)
            ->add('role', ChoiceType::class, ['choices' => User::ROLE_MAP])
            ->add('isBlocked', CheckboxType::class, ['required' => false])
            ->add('isActivated', CheckboxType::class, ['required' => false])
            ->add('photo', FileType::class, ['mapped' => false])
            ->add('submit', SubmitType::class);
    }
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
