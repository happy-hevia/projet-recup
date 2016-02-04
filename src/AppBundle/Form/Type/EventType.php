<?php
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

Class EventType extends AbstractType
{
	public function configureOptions(OptionsResolver $resolver) {
		$resolver->setDefaults(array(
				'data_class' => 'AppBundle\Entity\Evenement',
		));
	}
	
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('title')
		->add('author', TextType::class)
		->add('day', null, array('widget' => 'single_text'))
		->add('hour', null, array('widget' => 'single_text'))
		->add('file',null , array('required' => false))
		->add('content', null, array( 'attr' => array('class' => 'tinymce')))
		->add('submit', SubmitType::class, array('attr' => array('value' => 'valider')));
	}
}