<?php 
// src/AppBundle/Form/Type/LearnerPersonalType.php
namespace AppBundle\Form\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;

//this class builds the form that is used to add/edit a learner's personal details
class LearnerPersonalType extends AbstractType
{
	
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		//add the form fields
		$builder
		->add('idlwd','text', array(
				'label' => 'Learner Identification Number',
				'constraints' => array(new NotBlank(), new Regex(array(
					'pattern'=>'#\d{16}#',
					'message'=>'This field must be a 16 digit number'
					)),
				)
			)
		)
		->add('first_name','text', array(
			'label' => 'First name(s)',
			'constraints' => array(new NotBlank()),
			)
		)
		->add('last_name', 'text', array(
			'label' => 'Last name',
			'constraints' => array(new NotBlank()),
			)
		)
		->add('home_address', 'textarea', array(
			'label' => 'Present address',
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
		)
		->add('distance_to_school', 'choice', array(
			'label' => 'Estimated distance to school (Km)',
			'choices' => array('<1'=>'Less than 1km', '1-5'=>'between 1 and 5km', '>5'=>'More than 5km'),
			'constraints' => array(new NotBlank()),
			)
		)
		->add('std', 'integer', array(
			'label' => 'Standard',
			'constraints' => array(
				new NotBlank(),
				new Type(array('type'=>'integer','message'=>'Please enter a valid std value')),
				new Range(array('min'=> 1,'max'=>8, 'invalidMessage'=>'Please enter a value between 1 and 8')),
				)
			)
		)
		->add('means_to_school', 'choice', array(
			'label' => 'Means of travelling to school',
			'placeholder' => '--Means of travelling to school--',
			'choices' => array(
				'bus' => 'Bus',
                            'carried' => 'Carried',
				'walking' => 'Walking',
				'bicycle' => 'Bicycle',
				'tricycle'=> 'Tricycle',
				'wheel chair'=>'Wheel Chair',
				'other'=> 'Other'
				),
			'constraints' => array(new NotBlank()),
			))
                ->add('status_of_parent', 'choice', array(
                'label' => 'Status of parent', 
                'placeholder' => '--status of parent--',               
                'choices' => array(
                        'living' => 'Living',
                        'deceased' => 'Deceased'
                        ),
                'constraints' => array(new NotBlank()),
                ))
		->add('other_means', 'text', array(
			'label' => 'Other means',
			'required' => false,
			'constraints' => array(new NotBlank())
			))
		//The following are fields for guardian
		->add('idguardian','hidden', array(
			)
		)
		->add('gfirst_name','text', array(
			'label' => 'First name(s)',
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
			'label' => 'Postal address',
			'constraints' => array(new NotBlank()),
			)
		)
		->add('occupation','text', array(
			'label' => 'Occupation',
			'constraints' => array(new NotBlank()),
			)
		)
		->add('district','text', array(
			'label' => 'District',
			'constraints' => array(new NotBlank()),
			)
		)
		->add('guardian_relationship', 'choice', array(
			'label' => 'Relationship',
			'placeholder' => '--relationship--',
			'choices' => array(
				'parent'=>'parent',
				'sibling'=>'sibling',
				'uncle/aunt' => 'uncle/aunt',
				'grandparent' => 'grandparent',
				'other relative' => 'other relative',
				'other non-relative'=>'other non-relative'),
			'constraints' => array(new NotBlank()),
			'expanded' => false,
			'multiple' => false,
			)
		)
		->add('non_relative','text', array(
			'label' => 'Specify Other Non-Relative',
			'required' => false,
			'constraints' => array(new NotBlank()),
			)
		)
		->add('save','submit', array(
			'label' => 'save',
			)
		)->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
			$learner = $event->getData();
			$form = $event->getForm();

			if (!$learner) {
				return;
			}
			
			//remove non-blank constraint from other_means field based on the value of the means_to_school field
			if($learner['means_to_school'] != 'other'){
				$form->add('other_means', 'text', array(
			        'label' => 'Other means',
			        'required' => false,
			    ));
			}

			//remove non_relative field based on the value of guardian_relationship field
			if($learner['guardian_relationship'] != 'other non-relative'){
				$form->add('non_relative', 'text', array(
			        'label' => 'Specify Other Non-Relative',
			        'required' => false,
			    ));
			}
			
		});
	}
	public function getName()
	{
		return 'learner_personal';
	}
}
?>