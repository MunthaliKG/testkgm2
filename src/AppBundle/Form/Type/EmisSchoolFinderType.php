<?php 
// src/AppBundle/Form/Type/SchoolFinderType.php
namespace AppBundle\Form\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

//this class build the form that is used to select a school using district name and school name
class EmisSchoolFinderType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		//add the form fields
		$builder
		->add('emiscode', 'integer', array(
			'label' => 'School EMIS Code',
			'constraints' => array(new NotBlank(), new Type(array('type'=>'integer', 'message'=>'Please enter a valid EMIS code'))),
			'attr' => array('min'=>500001)
			)
		)
		->add('findschool', 'submit', array('label'=>'Find School')); 
	}
	public function getName()
	{
		return 'emisschoolfinder';
	}
}
?>