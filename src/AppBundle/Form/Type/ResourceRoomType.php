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
                )
        )
        ->add('date_procured','date', array(
                'label' => 'Date Procured',
                'widget' => 'single_text',
                'format' => 'dd-MM-yyyy',
                'constraints' => array(new NotBlank()),
                'attr' => array('class'=>'datepicker','data-date-format'=>'dd-mm-yyyy'),
                )
        )
        ->add('year_recorded', 'datetime', array(
                'label' => 'Year Recorded',
                'widget' => 'single_text',
                'format' => 'yyyy',
                'attr' => array('class'=>'datepicker','data-date-format'=>'yyyy '),
                'constraints' => array(new NotBlank()),
                )
        )                
        ->add('state', 'choice', array(
                'placeholder' => 'Status of need',
                'label' => 'State',
                'choices' => array('Good'=>'Good','Average'=>'Average','Bad'=>'Bad'),
                'constraints' => array(new NotBlank()),
                'expanded' => false,
                'multiple' => false,
                )
        )
        ->add('available_in', 'choice', array(
                'label' => 'Available',
                'choices' => array('With Learner'=> 'With Learner', 'Resource room'=>'Resource room', 'Else Where'=>'Other'),
                'expanded' => true,
                'multiple' => false,)
        )
        ->add('quantity', 'integer', array(
           'label' => 'Quantity',
           // 'attr' => array('min'=>1),
           'constraints' => array(
                new NotBlank(),
                new Range(array('min'=> 1))
            )		
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
 
    /*public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Need',
        ));
    }*/

}
?>


