<?php

namespace SnowTricks\HomeBundle\Form;

use SnowTricks\HomeBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use SnowTricks\HomeBundle\Repository\ImageRepository;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserRegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	$builder
            ->add('pseudo', TextType::class)
            ->add('email', EmailType::class)
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class
            ])
            ->add('avatar', ImageType::class,[
                "required" => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
    	 $resolver->setDefaults([
            'data_class' => User::class
        ]);
    }
}