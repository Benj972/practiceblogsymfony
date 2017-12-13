<?php

namespace SnowTricks\HomeBundle\Form;

use SnowTricks\HomeBundle\Entity\User;
use SnowTricks\HomeBundle\Form\Model\ChangePassword;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	$builder
    		->add('oldplainPassword', PasswordType::class)
       		->add('newplainPassword', RepeatedType::class, [
            	'type' => PasswordType::class
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ChangePassword::class
        ]);
    }

}