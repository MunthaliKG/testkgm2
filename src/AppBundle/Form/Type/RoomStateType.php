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
		->add('idRoom','text', array(
			'label' => 'Material Id',
			'constraints' => array(new NotBlank()),
			)
		)
		->add('emiscode','hidden', array(
			'label' => 'EMIS Code',
			)
		)
		->add('year', 'date', array(
			'label' => 'Year',
			'widget' => 'single_text',
			'format' => 'dd-MM-yyyy',
			'constraints' => array(new NotBlank()),
			'attr' => array('class'=>'datepicker','data-date-format'=>'dd-MM-yyyy'),
			)
		)
                ->add('enoughLight', 'choice', array(
			'label' => 'Enough Lighting',
			'choices' => array('Y'=>'Yes','N'=>'No'),
			'constraints' => array(new NotBlank()),
			'expanded' => true,
			'multiple' => false,
			)
                )
		->add('enoughSpace', 'choice', array(
			'label' => 'Enough Space',
			'choices' => array('Y'=>'Yes','N'=>'No'),
			'constraints' => array(new NotBlank()),
			'expanded' => true,
			'multiple' => false,
			)
                )
                ->add('adaptiveChairs', 'integer', array(
			'label' => 'Adaptive Chairs',
			'constraints' => array(new NotBlank()),		
			)
                )
                ->add('accessible', 'choice', array(
			'label' => 'Accesible',
			'choices' => array('Y'=>'Yes','N'=>'No'),
			'constraints' => array(new NotBlank()),
			'expanded' => true,
			'multiple' => false,
			)
                )
                ->add('enoughVentilation', 'choice', array(
			'label' => 'Enough Ventilation',
			'choices' => array('Y'=>'Yes','N'=>'No'),
			'constraints' => array(new NotBlank()),
			'expanded' => true,
			'multiple' => false,
			)
                )
                ->add('otherObservations', 'text', array(
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