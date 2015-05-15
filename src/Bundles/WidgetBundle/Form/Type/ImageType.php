<?php
/**
 * Created by PhpStorm.
 * User: Lamudi
 * Date: 14/05/15
 * Time: 15:53
 */

namespace Bundles\WidgetBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Bundles\WidgetBundle\Entity\Users;

class ImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setAction('/create_image')
            ->add('hash', 'hidden')
            ->add('width', 'integer', array('pattern' => '[0-9]{1,3}'))
            ->add('height', 'integer', array('pattern' => '[0-9]{1,3}'))
            ->add('bgColor', 'text', array('pattern' => '[0-9a-fA-F]{3,6}'))
            ->add('textColor', 'text', array('pattern' => '[0-9a-fA-F]+'))
        ;
    }

//    public function setDefaultOptions(OptionsResolverInterface $resolver)
//    {
//        $resolver->setDefaults(array(
//            'data_class' => 'Bundles\WidgetBundle\Entity\Users'
//        ));
//    }

    public function getName()
    {
        return 'image';
    }
}