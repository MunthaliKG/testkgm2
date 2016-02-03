<?php 
// src/AppBundle/Form/Type/SchoolFinderType.php
namespace AppBundle\Form\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;

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
			'label' => 'First name(s)',
			'constraints' => array(new NotBlank()),
			)
		)
		->add('slast_name', 'text', array(
			'label' => 'Last name',
			'constraints' => array(new NotBlank()),
			)
		)                            
                ->add('teacher_type', 'choice', array(
                    'label' => 'Teacher Type',
                    'choices' => array('snt'=>'SNT', 'regular'=>'Regular'),
                    'expanded' => true,
                    'constraints' => array(new NotBlank()),
                    'multiple' => false,)
		)
		->add('s_sex', 'choice', array(
			'label' => 'Sex',
			'choices' => array('M'=>'Male','F'=>'Female'),
                        'constraints' => array(new NotBlank()),
			'expanded' => true,
			'multiple' => false,
			)
		)
		->add('qualification', 'choice', array(
			'label' => 'Qualification',
                        'placeholder' => '--Qualification--',
			'choices' => array('certificate'=>'Certicate','diploma'=>'Diploma', 'degree'=>'Degree'),
			'expanded' => false,
                        'constraints' => array(new NotBlank()),
			'multiple' => false,)
		)                               
                ->add('cpd_training', 'choice', array(
			'label' => 'Attended CPD Training',
			'choices' => array('yes'=>'Yes', 'no'=>'No'),
			'expanded' => false,
                        'placeholder' => '--CPD Training--',
                        'constraints' => array(new NotBlank()),
			'multiple' => false,)
		)
		->add('speciality', 'choice', array(
			'label' => 'Speciality',
                        'placeholder' => '--SNT Speciality--',
			'choices' => array('HI'=>'Hearing Impairment', 'VI'=>'Visual Impairment', 'LD'=>'Learning Difficulty', 'DB'=>'Deaf/Blind'),
			'expanded' => false,
                        'constraints' => array(new NotBlank()),
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
                ->add('snt_type', 'choice', array(
                    'label' => 'SNT Type',
                    'choices' => array('Itinerant'=>'Itinerant', 'Stationed'=>'Stationed'),
                    'expanded' => false,
                    'placeholder' => '--SNT Type--',
                    'constraints' => array(new NotBlank()),
                    'multiple' => false,)
		)
                ->add('no_of_visits', 'integer', array(
			'label' => 'No of Visits',
			'attr' => array('min'=>0),
			'constraints' => array(new NotBlank()),
			)
		)
		->add('save','submit', array(
			'label' => 'save',
			)
		)
                //remoe some form field before submiting
                ->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
			$teacher = $event->getData();
			$form = $event->getForm();

			if (!$teacher) {
                            return;
			}			
			//remove different fields based on the value of other fields
			if($teacher['teacher_type'] == 'snt'){
                            //$form->remove('cpd_training');
                            $form->add('cpd_training', 'choice', array(
                                    'label' => 'Attended CPD Training',
                                    'choices' => array('yes'=>'Yes', 'no'=>'No'),
                                    'expanded' => false,
                                    'placeholder' => '--CPD Training--',
                                    //'constraints' => array(new NotBlank()),
                                    'multiple' => false,)
                            );
                        } 
                        if($teacher['teacher_type'] == 'regular') {
                            //$form->remove('year_started');
                            //$form->remove('qualification');
                            $form->add('qualification', 'choice', array(
                                'label' => 'Qualification',
                                'placeholder' => '--Qualification--',
                                'choices' => array('certificate'=>'Certicate','diploma'=>'Diploma', 'degree'=>'Degree'),
                                'expanded' => false,
                                //'constraints' => array(new NotBlank()),
                                'multiple' => false,));
                            //$form->remove('speciality');
                            $form->add('speciality', 'choice', array(
                                'label' => 'Speciality',
                                'placeholder' => '--SNT Speciality--',
                                'choices' => array('HI'=>'Hearing Impairment', 'VI'=>'Visual Impairment', 'LD'=>'Learning Difficulty', 'DB'=>'Deaf/Blind'),
                                'expanded' => false,
                                //'constraints' => array(new NotBlank()),
                                'multiple' => false,));
                                   
                            $form->add('year_started', 'datetime', array(
                                'label' => 'Year Started Teaching',
                                'widget' => 'single_text',
                                'format' => 'yyyy',
                                'attr' => array('class'=>'datepicker','data-date-format'=>'yyyy '),
                                //'constraints' => array(new NotBlank()),
                                ));                           
                            //$form->remove('snt_type');
                            $form->add('snt_type', 'choice', array(
                                'label' => 'SNT Type',
                                'choices' => array('Itinerant'=>'Itinerant', 'Stationed'=>'Stationed'),
                                'expanded' => false,
                                'placeholder' => '--SNT Type--',
                                //'constraints' => array(new NotBlank()),
                                'multiple' => false,)
                            );
                            //$form->remove('no_of_visits');
                            $form->add('no_of_visits', 'integer', array(
                                'label' => 'No of Visits',
                                'attr' => array('min'=>0),
                                //'constraints' => array(new NotBlank()),
                                )
                            );
                        }			
		});
	}
	public function getName()
	{
            return 'teacher';
	}
}

