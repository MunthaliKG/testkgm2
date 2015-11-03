<?php 
// src/AppBundle/Form/Type/LearnerPerformanceType.php
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

//this class builds the form that is used to enter a performance record for a student
class LearnerPerformanceType extends AbstractType
{
	
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		//add the form fields	
		$builder
		->add('std','choice', array(
			'label' => 'Standard',
			'placeholder' => 'standard',
			'choices' => array(
				1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6, 7 => 7, 8 => 8,
				),
			'constraints' => array(new NotBlank()),
			)
		)
		->add('year', 'datetime', array(
			'label' => 'Year',
			'widget' => 'single_text',
			'format' => 'yyyy',
			'attr' => array('class'=>'datepicker','data-date-format'=>'yyyy '),
			'constraints' => array(new NotBlank()),
			)
		)
		->add('term','choice', array(
			'choices' => array(1 => 1, 2 => 2, 3 => 3),
			'expanded' => true,
			'multiple' => false,)
		)
		->add('grade','choice', array(
                    'placeholder' => 'Choose grade',
			'choices' => array(
				1 => '0-40',
				2 => '41-50', 
				3 => '51-65',
				4 => '66-75',
				5 => '76-100',
				)
			)
		)
		->add('teachercomment', 'textarea', array(
			'label' => "Teacher's Comment",
			'required' => false,
			)
		)
		->add('save', 'submit', array(
			'label' => 'save')
		);
	}
	public function getName()
	{
		return 'learner_performance';
	}
}
?>