<?php


namespace SnowTricks\HomeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\HttpFoundation\Request;
use SnowTricks\HomeBundle\Form\Model\ResetPassword;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use SnowTricks\HomeBundle\Manager\UserManagerInterface;

class ResetPasswordType extends AbstractType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('plainPassword', RepeatedType::class, array(
            'first_options'  => array('label' => PasswordType::class),
            'second_options' => array('label' => RepeatedType::class),
            'type'           => PasswordType::class,
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'SnowTricks\HomeBundle\Form\Model\ResetPassword',
        ]);
    }
}
