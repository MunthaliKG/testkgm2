<?php 
// src/AppBundle/Form/Type/SchoolFinderType.php
namespace AppBundle\Controller;
namespace AppBundle\Form\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;


//this class build the form that is used to select a teacher using district name and school name
class ResourceRoomType extends AbstractType
{
    private $needs;
    private $rooms;

    public function __construct($needs, $rooms)
    {
        $this->choices = $needs;
        $this->rooms = $rooms;
    }
    public function buildForm(FormBuilderInterface $builder, array $options){
        $needs = $this->needs;
        $rooms = $this->rooms;
        $builder
        ->add('idneed_2','hidden')
        ->add('idneed','choice', array(
               'label' => 'Special Needs Item',
               'choices' => $needs,
                //'constraints' => array(new NotBlank()),
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
        ->add('year_recorded', 'date', array(
                'label' => 'Year Recorded',
                'widget' => 'single_text',
                'format' => 'dd-MM-yyyy',
                'attr' => array('class'=>'datepicker','data-date-format'=>'dd-mm-yyyy'),
                'constraints' => array(new NotBlank()),
                )
        )
        ->add('state', 'choice', array(
                'label' => 'State',
                'choices' => array('Good'=>'Good','Average'=>'Average','Bad'=>'Bad'),
                'constraints' => array(new NotBlank()),
                'expanded' => false,
                'multiple' => false,
                )
        )
        ->add('available_in', 'choice', array(
                'label' => 'Available in:',
                'choices' => $rooms,
                'expanded' => false,
                'multiple' => false,)
        )
        ->add('quantity', 'integer', array(
           'label' => 'Quantity',
           'constraints' => array(new NotBlank()),)		
           )
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


