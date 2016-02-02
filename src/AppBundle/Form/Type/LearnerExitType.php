<?php 
// src/AppBundle/Form/Type/SchoolFinderType.php
namespace AppBundle\Form\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;

//this class build the form that is used to select a school using district name and school name
class LearnerExitType extends AbstractType
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
		->add('reason', 'choice', array(
			'label' => 'Reason for Exit',
			'placeholder' => '--reason for exit--',
			'choices' => [
				'completed' => 'Completed', 
				'sickness' => 'Sickness', 
				'pregnancy' => 'Pregnancy',
				'death' => 'Death', 
				'distance' => 'Distance',
				'unconducive facilities' => 'Unconducive facilities',
				'other' => 'Other'
				],
			'constraints' => array(new NotBlank()),
			)
		)
		->add('other_reason', 'textarea', array(
			'label' => 'Other Reason',
			'required' => false,
			)
		)
		->add('save', 'submit', array('label'=>'Save'))
		->addEventListener(
            FormEvents::PRE_SUBMIT,
            function (FormEvent $event){
                $form = $event->getForm();
                $data = $event->getData();

                if($data['reason'] == 'other'){
                	$form->add('other_reason', 'textarea', array(
                		'label' => 'Other Reason',
                		'constraints' => array(new NotBlank(array('message'=>'You are required to fill this field if you select "Other" from above'))),
                		'required' => false,
                		)
                	);
                }
            }
        );
	}
	public function getName()
	{
		return 'learner_exit';
	}
}
?>