<?php 
// src/AppBundle/Form/Type/UserFilterType.php
namespace AppBundle\Form\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

//this class build the form that is used to filter user lists
class UserFilterType extends AbstractType
{

	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		//add the form fields
		$builder
		->add('show_disabled', 'choice', array(
			'label' => '',
			'choices' => array(1 =>'show disabled accounts'),
			'expanded' => true,
			'multiple' => true,
			'required' => false,
			)
		)
		->add('first_name', 'text', array(
			'label' => 'First Name',
			'attr' => array(
					'placeholder' => 'First Name'
				),
			'required' => false,
			)
		)
		->add('last_name', 'text', array(
			'label' => 'Last Name',
			'attr' => array(
					'placeholder' => 'Last Name'
				),
			'required' => false,
			)
		)
		->add('filter','submit', array(
			'label' => 'Filter')
		)
		->add('clear','submit', array(
			'label' => 'Clear',
			'attr' => array(
				'title' => 'clear all filters'
				)
			)
		); 
	}
	public function getName()
	{
		return 'userfilter';
	}
}
?>