<?php 
// src/AppBundle/Form/Type/SchoolFinderType.php
namespace AppBundle\Form\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

//this class build the form that is used to select a teacher using district name and school name
class TeacherType extends AbstractType
{
	
	public function buildForm(FormBuilderInterface $builder, array $options){
		$builder
		->add('idsnt','text', array(
			'label' => 'Teacher Id',
			'constraints' => array(new NotBlank()),
			)
		)
                ->add('sfirst_name','text', array(
			'label' => 'First name',
			'constraints' => array(new NotBlank()),
			)
		)
		->add('slast_name', 'text', array(
			'label' => 'Last name',
			'constraints' => array(new NotBlank()),
			)
		)
		->add('sinitials', 'text', array(
			'label' => 'Initials',
			'required' => false,
			)
		)
                ->add('s_dob', 'date', array(
			'label' => 'Date of birth',
			'widget' => 'single_text',
			'format' => 'dd-MM-yyyy',
			'constraints' => array(new NotBlank()),
			'attr' => array('class'=>'datepicker','data-date-format'=>'dd-mm-yyyy'),
			)
		)
		->add('s_sex', 'choice', array(
			'label' => 'Sex',
			'choices' => array('M'=>'M','F'=>'F'),
			'constraints' => array(new NotBlank()),
			'expanded' => true,
			'multiple' => false,
			)
		)
		->add('qualification', 'choice', array(
			'label' => 'Qualification',
			'choices' => array('certificate'=>'certificate', 'diploma'=>'diploma', 'degree'=>'degree'),
			'expanded' => true,
			'multiple' => false,)
		)
		->add('speciality', 'choice', array(
			'label' => 'Speciality',
			'choices' => array('HI'=>'HI', 'VI'=>'VI', 'LD'=>'LD'),
			'expanded' => true,
			'multiple' => true,)
		)
		->add('year_started', 'datetime', array(
			'label' => 'Year Started',
			'widget' => 'single_text',
			'format' => 'yyyy',
			'attr' => array('class'=>'datepicker','data-date-format'=>'yyyy '),
			'constraints' => array(new NotBlank()),
			)
		)
                //This is for school_has_snt table
                ->add('year', 'datetime', array(
			'label' => 'Current working year at School',
			'widget' => 'single_text',
			'format' => 'yyyy',
			'attr' => array('class'=>'datepicker','data-date-format'=>'yyyy '),
			'constraints' => array(new NotBlank()),
			)
		)
		->add('next','submit', array(
			'label' => 'next',
			)
		);
	}
	public function getName()
	{
		return 'teacher';
	}
}
?>
