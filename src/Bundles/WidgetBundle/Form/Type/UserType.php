<?php
/**
 * Created by PhpStorm.
 * User: Lamudi
 * Date: 12/05/15
 * Time: 16:37
 */

namespace Bundles\WidgetBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Bundles\WidgetBundle\Entity\Users;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('userName')
            ->add('rate')
            ->add('hash', 'hidden')
            ->add('status', 'choice', array(
                'choices' =>  array(
                    'active'   => Users::STATUS_ACTIVE,
                    'inactive' => Users::STATUS_INACTIVE,
                    'blocked'  => Users::STATUS_BLOCKED
                )
            ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Bundles\WidgetBundle\Entity\Users'
        ));
    }

    public function getName()
    {
        return 'user';
    }
}