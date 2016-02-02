<?php 
// src/AppBundle/Form/Type/SchoolFinderType.php
namespace AppBundle\Controller;
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Range;


//this class build the form that is used to select a teacher using district name and school name
class ResourceRoomType extends AbstractType
{
    private $needs;    

    public function __construct($needs)
    {
        $this->needs = $needs;        
    }
    public function buildForm(FormBuilderInterface $builder, array $options){
        $needs = $this->needs;       
        $builder
        ->add('idneed_2','hidden')
        ->add('idneed','choice', array(
           'placeholder' => 'Choose special need item',
           'label' => 'Special Needs Item',
           'choices' => $needs,                
            'expanded' => false,
            'multiple' => false,
            )) 
        ->add('year_recorded', 'datetime', array(
                'label' => 'Year Recorded',
                'widget' => 'single_text',
                'format' => 'yyyy',
                'attr' => array('class'=>'datepicker','data-date-format'=>'yyyy '),
                'constraints' => array(new NotBlank()),
                ))                
        ->add('quantity_available', 'integer', array(
           'label' => 'Quantity Available',
           'constraints' => array(
                new NotBlank(),
                new Range(array('min'=> 1))
            )))
        ->add('quantity_in_use', 'integer', array(
           'label' => 'Quantity In Use',
           'constraints' => array(
                new NotBlank(),
                new Range(array('min'=> 1))
            )))
        ->add('quantity_required', 'integer', array(
           'label' => 'Quantity Required',
           'constraints' => array(
                new NotBlank(),
                new Range(array('min'=> 1))
            )		))
        ->add('available', 'choice', array(
           'label' => 'Available',
           'choices' => array('No'=>'No','Yes'=>'Yes'),
			'constraints' => array(new NotBlank()),
			'expanded' => false,
			'multiple' => false,		
           ))
        ->add('provided_by','text', array(
            'label' => 'Provided By',
            'constraints' => array(new NotBlank()),
            ))
        ->add('save','submit', array(
			'label' => 'save',
			)
		);
    }
    public function getName()
    {
        return 'resourceRoom';
    }

}
?>


