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
		->add('year', 'datetime', array(
			'label' => 'Year',
			'widget' => 'single_text',
                        'format' => 'yyyy',
			'attr' => array('class'=>'datepicker','data-date-format'=>'yyyy '),
			'constraints' => array(new NotBlank()),
			)
		)
                ->add('enough_light', 'choice', array(
			'label' => 'Enough Lighting',
			'choices' => array('No'=>'No','Yes'=>'Yes'),
			'constraints' => array(new NotBlank()),
			'expanded' => false,
			'multiple' => false,
			)
                )
		->add('enough_space', 'choice', array(
			'label' => 'Enough Space',
			'choices' => array('No'=>'No','Yes'=>'Yes'),
			'constraints' => array(new NotBlank()),
			'expanded' => false,
			'multiple' => false,
			)
                )
                ->add('adaptive_chairs', 'choice', array(
			'label' => 'Adaptive Chairs',
                        'choices' => array('No'=>'No','Yes'=>'Yes'),
			'constraints' => array(new NotBlank()),
			'expanded' => false,
			'multiple' => false,		
			)
                )
                ->add('access', 'choice', array(
			'label' => 'Accessible',
			'choices' => array('No'=>'No','Yes'=>'Yes'),
			'constraints' => array(new NotBlank()),
			'expanded' => false,
			'multiple' => false,
			)
                )
                ->add('room_type', 'choice', array(
			'label' => 'Room Type',
			'choices' => array('Permanent'=>'Permanent', 'Temporary'=>'Temporary'),
			'constraints' => array(new NotBlank()),
			'expanded' => false,
			'multiple' => false,
			)
                )
                ->add('enough_ventilation', 'choice', array(
			'label' => 'Enough Ventilation',
			'choices' => array('No'=>'No','Yes'=>'Yes'),
			'constraints' => array(new NotBlank()),
			'expanded' => false,
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
		return 'material';
	}
}
?>