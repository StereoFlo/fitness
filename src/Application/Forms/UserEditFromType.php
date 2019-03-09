<?php

namespace Application\Forms;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class UserEditFromType
 * @package Application\Forms
 */
class UserEditFromType extends AbstractUserFormType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
    }
}
