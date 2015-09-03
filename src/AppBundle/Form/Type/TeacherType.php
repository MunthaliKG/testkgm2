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
		->add('employment_number','text', array(
			'label' => 'Employment Number',
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
                        'placeholder' => '--Qualification--',
			'choices' => array('diploma'=>'diploma', 'degree'=>'degree'),
			'expanded' => false,
			'multiple' => false,)
		)
                ->add('snt_type', 'choice', array(
			'label' => 'Teacher Type',
			'choices' => array('Itinerant'=>'Itinerant', 'Resident'=>'Resident'),
			'expanded' => true,
			'multiple' => false,)
		)
		->add('speciality', 'choice', array(
			'label' => 'Speciality',
			'choices' => array('HI'=>'HI', 'VI'=>'VI', 'LD'=>'LD'),
			'expanded' => true,
			'multiple' => true,)
		)
		->add('year_started', 'date', array(
			'label' => 'Year Started Teaching',
			'widget' => 'single_text',
			'format' => 'dd-MM-yyyy',
			'attr' => array('class'=>'datepicker','data-date-format'=>'dd-mm-yyyy'),
			'constraints' => array(new NotBlank()),
			)
		)
                //This is for school_has_snt table
                ->add('year', 'date', array(
			'label' => 'Current working year at School',
			'widget' => 'single_text',
			'format' => 'dd-MM-yyyy',
			'attr' => array('class'=>'datepicker','data-date-format'=>'dd-mm-yyyy'),
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
