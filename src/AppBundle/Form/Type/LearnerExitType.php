<?php 
// src/AppBundle/Form/Type/SchoolFinderType.php
namespace AppBundle\Form\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

//this class build the form that is used to select a school using district name and school name
class LearnerExitType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		//add the form fields
		$builder
		->add('reason', 'choice', array(
			'label' => 'Reason for Exit',
			'placeholder' => 'Choose reason for exit',
			'choices' => [
				'completed' => 'Completed', 
				'sickness' => 'Sickness', 
				'pregnancy' => 'Pregnancy',
				'death' => 'Death', 
				'distance' => 'Distance',
				'unconducive facilities' => 'Unconducive facilities',
				'other' = 'Other'
				]
			'constraints' => array(new NotBlank()),
			)
		)
		->add('other_reason', 'textarea', array(
			'label' => 'Other')
		)
		->add('findschool', 'submit', array('label'=>'Find School')); 
	}
	public function getName()
	{
		return 'emisschoolfinder';
	}
}
?>