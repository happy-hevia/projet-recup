<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DescriptionLieuType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('localisation')
            ->add('nom')
            ->add('type', ChoiceType::class, array(
                'choices'  => array(
                    'supermarché' => "supermarche",
                    'épicerie' => "epicerie",
                    'boulangerie' => "boulangerie",
                    'marché' => "marche",
                    'autre' => "autre",
                    "" => "",
                ),
            ))
            ->add('acces', ChoiceType::class, array(
                'choices'  => array(
                    'facile' => "facile",
                    'difficile' => "difficile",
                    "" => "",
                ),
            ))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\DescriptionLieu'
        ));
    }
}
