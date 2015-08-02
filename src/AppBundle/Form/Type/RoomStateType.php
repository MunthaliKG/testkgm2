<?php 
// src/AppBundle/Form/Type/SchoolFinderType.php
namespace AppBundle\Form\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

//this class build the form that is used to select a school using district name and school name
class RoomStateType extends AbstractType
{
	
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		//add the form fields
		
		$builder
		->add('room_id','text', array(
			'label' => 'Material Id',
			'constraints' => array(new NotBlank()),
			)
		)
		/*->add('emiscode','hidden', array(
			'label' => 'EMIS Code',
			)
                )
                 * 
                 */
		->add('year_started', 'datetime', array(
			'label' => 'Year Started',
			'widget' => 'single_text',
			'format' => 'yyyy',
			'constraints' => array(new NotBlank()),
			'attr' => array('class'=>'datepicker','data-date-format'=>'yyyy'),
			)
		)
                ->add('enough_light', 'choice', array(
			'label' => 'Enough Lighting',
			'choices' => array('Yes'=>'Yes','No'=>'No'),
			'constraints' => array(new NotBlank()),
			'expanded' => true,
			'multiple' => false,
			)
                )
		->add('enough_space', 'choice', array(
			'label' => 'Enough Space',
			'choices' => array('Yes'=>'Yes','No'=>'No'),
			'constraints' => array(new NotBlank()),
			'expanded' => true,
			'multiple' => false,
			)
                )
                ->add('adaptive_chairs', 'integer', array(
			'label' => 'Adaptive Chairs',
			'constraints' => array(new NotBlank()),		
			)
                )
                ->add('access', 'choice', array(
			'label' => 'Accessible',
			'choices' => array('Yes'=>'Yes','No'=>'No'),
			'constraints' => array(new NotBlank()),
			'expanded' => true,
			'multiple' => false,
			)
                )
                ->add('enough_ventilation', 'choice', array(
			'label' => 'Enough Ventilation',
			'choices' => array('Yes'=>'Yes','No'=>'No'),
			'constraints' => array(new NotBlank()),
			'expanded' => true,
			'multiple' => false,
			)
                )
                ->add('other_observations', 'text', array(
			'label' => 'Other Observations',
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