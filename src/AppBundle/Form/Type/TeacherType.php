<?php 
// src/AppBundle/Form/Type/SchoolFinderType.php
namespace AppBundle\Form\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

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
                        'placeholder' => '--Gender/Sex--',
			'choices' => array('M'=>'Male','F'=>'Female'),
                        'constraints' => array(new NotBlank()),
			'expanded' => false,
			'multiple' => false,
			)
		)
		->add('qualification', 'choice', array(
			'label' => 'Qualification',
                        'placeholder' => '--Qualification--',
			'choices' => array('msce'=>'MSCE','certificate'=>'Certificate','diploma'=>'Diploma', 'degree'=>'Degree'),
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
                        'placeholder' => '--SNT Speciality--',
			'choices' => array('HI'=>'Hearing Impairment', 'VI'=>'Visual Impairment', 'LD'=>'Learning Difficulty', 'DB'=>'Deaf/Blind'),
			'expanded' => false,
			'multiple' => false,)
		)
		->add('year_started', 'datetime', array(
			'label' => 'Year Started Teaching',
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
                ->add('other_specialities', 'choice', array(
			'label' => 'Other Specialities',
			'choices' => array('HI'=>'Hearing Impairment', 'VI'=>'Visual Impairment', 'LD'=>'Learning Difficulty', 'DB'=>'Deaf/Blind'),
			'expanded' => true,
			'multiple' => true,
                        'required' => false,)
                )
		->add('save','submit', array(
			'label' => 'save',
			)
		)
//                        ->addEventListener(
//                    FormEvents::PRE_SUBMIT,
//                    function (FormEvent $event){
//                        $form = $event->getForm();
//                        $data = $event->getData();
//
//                        if($data['speciality'] == 'VI'){
//                            $form->add('other_specialities', 'choice', array(
//                                'label' => 'Other Specialities',
//                                'choices' => array('HI'=>'Hearing Impairment', 'LD'=>'Learning Difficulty', 'DB'=>'Deaf/Blind'),
//                                'expanded' => true,
//                                'multiple' => true,
//                                'required' => false,)
//                            );
//                        }
//                    }
//                )
                    ;
	}
	public function getName()
	{
		return 'teacher';
	}
}

