<?php


namespace SnowTricks\HomeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use SnowTricks\HomeBundle\Form\Model\RequestPassword;
use SnowTricks\HomeBundle\Manager\UserManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class RequestPasswordType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('email', TextType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'SnowTricks\HomeBundle\Form\Model\RequestPassword',
        ]);
    }
}
