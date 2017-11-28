<?php

namespace SnowTricks\HomeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TrickType extends AbstractType
{


    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('name', TextType::class)
        ->add('content', TextareaType::class)
        ->add('category', EntityType::class, array(
        'class'         => 'SnowTricksHomeBundle:Category',
        'choice_label'  => 'name',
        'multiple'      => true,
        ))
        ->add('images', CollectionType::class, array(
        'entry_type'   => ImageType::class,
        'allow_add'    => true,
        'allow_delete' => true
         ))
        ->add('save',  SubmitType::class);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SnowTricks\HomeBundle\Entity\Trick'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'snowtricks_homebundle_trick';
    }


}
