<?php 
// src/AppBundle/Form/Type/SchoolFinderType.php
namespace AppBundle\Form\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

//this class build the form that is used to select a school using district name and school name
class RoomStateType extends AbstractType
{
	
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		//add the form fields
		$builder
		->add('room_id','text', array(
			'label' => 'Room Id',
			'constraints' => array(new NotBlank()),
			)
		)		
                ->add('enough_light', 'choice', array(
			'label' => 'Enough Lighting',
                    'placeholder' => '--Lighting--',
			'choices' => array('no'=>'No','yes'=>'Yes'),
			'constraints' => array(new NotBlank()),
			'expanded' => false,
			'multiple' => false,
			)
                )
		->add('enough_space', 'choice', array(
			'label' => 'Enough Space',
                    'placeholder' => '--Space--',
			'choices' => array('no'=>'No','yes'=>'Yes'),
			'constraints' => array(new NotBlank()),
			'expanded' => false,
			'multiple' => false,
			)
                )
                ->add('noise_free', 'choice', array(
			'label' => 'Noise free',
			'choices' => array('no'=>'No','yes'=>'Yes'),
                    'placeholder' => '--Noise free--',
			'constraints' => array(new NotBlank()),
			'expanded' => false,
			'multiple' => false,
			)
                )
                ->add('adaptive_chairs', 'choice', array(
			'label' => 'Adaptive Chairs',
                    'placeholder' => '--Chairs--',
                        'choices' => array('no'=>'No','yes'=>'Yes'),
			'constraints' => array(new NotBlank()),
			'expanded' => false,
			'multiple' => false,		
			)
                )
                ->add('access', 'choice', array(
			'label' => 'Accessible',
			'choices' => array('no'=>'No','yes'=>'Yes'),
                    'placeholder' => '--Accessibility--',
			'constraints' => array(new NotBlank()),
			'expanded' => false,
			'multiple' => false,
			)
                )
                ->add('room_type', 'choice', array(
			'label' => 'Room Type',
			'choices' => array('Open air'=>'Open air','Permanent'=>'Permanent', 'Temporary'=>'Temporary'),
                    'placeholder' => '--Room type--',
			'constraints' => array(new NotBlank()),
			'expanded' => false,
			'multiple' => false,
			)
                )
                ->add('enough_ventilation', 'choice', array(
			'label' => 'Enough Ventilation',
                    'placeholder' => '--Ventilation--',
			'choices' => array('no'=>'No','yes'=>'Yes'),
			'constraints' => array(new NotBlank()),
			'expanded' => false,
			'multiple' => false,
			)
                )                
		->add('save','submit', array(
			'label' => 'save',
			));
	}
	public function getName()
	{
		return 'material';
	}
}
?>