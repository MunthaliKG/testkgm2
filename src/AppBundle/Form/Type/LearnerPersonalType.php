<?php 
// src/AppBundle/Form/Type/SchoolFinderType.php
namespace AppBundle\Form\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

//this class build the form that is used to select a school using district name and school name
class LearnerPersonalType extends AbstractType
{
	
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		//add the form fields
		
		$builder
		->add('idlwd','text', array(
			'label' => 'Learner Id',
			'constraints' => array(new NotBlank()),
			)
		)
		->add('first_name','text', array(
			'label' => 'First name',
			'constraints' => array(new NotBlank()),
			)
		)
		->add('last_name', 'text', array(
			'label' => 'Last name',
			'constraints' => array(new NotBlank()),
			)
		)
		->add('initials', 'text', array(
			'label' => 'Initials',
			'required' => false,
			)
		)
		->add('home_address', 'textarea', array(
			'label' => 'Home address',
			'constraints' => array(new NotBlank()),
			)
		)
		->add('sex', 'choice', array(
			'label' => 'Sex',
			'choices' => array('M'=>'M','F'=>'F'),
			'constraints' => array(new NotBlank()),
			'expanded' => true,
			'multiple' => false,
			)
		)
		->add('dob', 'date', array(
			'label' => 'Date of birth',
			'widget' => 'single_text',
			'format' => 'dd-MM-yyyy',
			'constraints' => array(new NotBlank()),
			'attr' => array('class'=>'datepicker','data-date-format'=>'dd-mm-yyyy'),
			)
		)//The following are fields for guardian
		->add('idguardian','hidden', array(
			)
		)
		->add('gfirst_name','text', array(
			'label' => 'First name',
			'constraints' => array(new NotBlank()),
			)
		)
		->add('glast_name', 'text', array(
			'label' => 'Last name',
			'constraints' => array(new NotBlank()),
			)
		)
		->add('gsex', 'choice', array(
			'label' => 'Sex',
			'choices' => array('M'=>'M','F'=>'F'),
			'constraints' => array(new NotBlank()),
			'expanded' => true,
			'multiple' => false,
			)
		)
		->add('gaddress', 'textarea', array(
			'label' => 'Home address',
			'constraints' => array(new NotBlank()),
			)
		)
		->add('gdob', 'date', array(
			'label' => 'Date of birth',
			'widget' => 'single_text',
			'format' => 'dd-MM-yyyy',
			'constraints' => array(new NotBlank()),
			'attr' => array('class'=>'datepicker','data-date-format'=>'dd-mm-yyyy'),
			)
		)
		->add('occupation','text', array(
			'label' => 'Occupation',
			'constraints' => array(new NotBlank()),
			)
		)
                ->add('income_level', 'choice', array(
			'label' => 'Income level',
			'choices' => array('low'=>'low','medium'=>'medium','high'=>'high'),
			'constraints' => array(new NotBlank()),
			'expanded' => true,
			'multiple' => false,
			)
		)
		->add('district','text', array(
			'label' => 'District',
			'constraints' => array(new NotBlank()),
			)
		)
		->add('guardian_relationship', 'choice', array(
			'label' => 'Relationship',
			'choices' => array('parent'=>'parent','sibling'=>'sibling','other'=>'other'),
			'constraints' => array(new NotBlank()),
			'expanded' => true,
			'multiple' => false,
			)
		)
		->add('save','submit', array(
			'label' => 'save',
			)
		);
	}
	public function getName()
	{
		return 'learner';
	}
}
?>