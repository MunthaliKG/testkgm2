<?php 
// src/AppBundle/Form/Type/SchoolFinderType.php
namespace AppBundle\Form\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;

//this class build the form that is used to select a school using district name and school name
class LearnerTransferType extends AbstractType
{
    private $schools;
    private $connection;

    public function __construct($schools, $connection)
    {
        $this->schools = $schools;
        $this->connection = $connection;
    }
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$schools = $this->schools;
                $connection = $this->connection;
                //add the form fields
		$builder
		->add('school', 'choice', array(
			'placeholder' => 'Choose School',
                       //'label' => 'Special Needs Item',
                       'choices' => $schools,                
                        'expanded' => false,
                        'multiple' => false,
			//'constraints' => array(new NotBlank()),
			)
		)
                ->add('std', 'choice', array(
                        'placeholder' => 'Choose learner',
			//'label' => 'Other Reason',
			'required' => false,
			)
		)                
		->add('save', 'submit', array('label'=>'Save'))
		->addEventListener(
            FormEvents::PRE_SUBMIT,
            function (FormEvent $event){
                $form = $event->getForm();
                $data = $event->getData();

                if($data['district']){
                    $zones = $connection->fetchAll('SELECT idzone, zone_name FROM zone WHERE district_iddistrict = ?',array($data['district']));                    
                    $zone_choices = array();
                    foreach ($zones as $key => $row) {
                        $zone_choices[$row['idzone']] = $row['zone_name'];
                    }
                	$form->add('zone', 'choice', array(
                		'label' => 'Other Reason',
                                'choices'=> $zone_choices,
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
