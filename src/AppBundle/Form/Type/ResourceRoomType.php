<?php 
// src/AppBundle/Form/Type/SchoolFinderType.php
namespace AppBundle\Controller;
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;

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
        ->add('quantity_available', 'integer', array(
           'label' => 'Quantity Available',
           'constraints' => array(
                new NotBlank(),
                new Range(array('min'=> 0))
            )))
        ->add('quantity_in_use', 'integer', array(
           'label' => 'Quantity In Use',
           'constraints' => array(
                new NotBlank(),
                new Range(array('min'=> 0))
            )))
        ->add('quantity_required', 'integer', array(
           'label' => 'Quantity Required',
           'constraints' => array(
                new NotBlank(),
                new Range(array('min'=> 1))
            )		))
        ->add('available', 'choice', array(
           'label' => 'Available',
            'placeholder' => '--Available resource--',
           'choices' => array('no'=>'No','yes'=>'Yes'),
			'constraints' => array(new NotBlank()),
			'expanded' => false,
			'multiple' => false,		
           ))
        ->add('provided_by','text', array(
            'label' => 'Provided By',
            'required' => false,
            //'constraints' => array(new NotBlank()),
            ))
        ->add('save','submit', array(
			'label' => 'save',
			)
		)
            ->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
			$material = $event->getData();
			$form = $event->getForm();
                        if(!$material){
                            return;
			}
                        if(isset($material['quantity_in_use']) || isset($material['quantity_available']) || isset($material['quantity_required'])){
                            //$form->remove('cpd_training');
                            $form->add('available', 'choice', array(
                            'label' => 'Available',
                             'placeholder' => '--Available resource--',
                            'choices' => array('no'=>'No','yes'=>'Yes'),
                                         //'constraints' => array(new NotBlank()),
                                         'expanded' => false,
                                         'multiple' => false,		
                            ));
                        }
            
                        if (isset($material['available'])) {
                            $form->add('quantity_available', 'integer', array(
                            'label' => 'Quantity Available',
                            //'constraints' => array(
//                                 new NotBlank(),
//                                 new Range(array('min'=> 0))
                             ));
                            $form->add('quantity_required', 'integer', array(
                            'label' => 'Quantity Required',
                            //'constraints' => array(
                                 //new NotBlank(),
                                 //new Range(array('min'=> 1))
                             ));
                            $form->add('quantity_in_use', 'integer', array(
                            'label' => 'Quantity In Use',
//                            'constraints' => array(
//                                 new NotBlank(),
//                                 new Range(array('min'=> 0))
                             ));
                        }
                        });

			
//                        $event->getForm()->add('idneed','choice', array(
//                            'placeholder' => 'Choose special need item',
//                            'label' => 'Special Needs Item',
//                            'choices' => $this->needs,
//                             'expanded' => false,
//                            'disabled' => false,
//                             'multiple' => false,
//                             ));
    }	
    public function getName()
    {
        return 'resourceRoom';
    }

}
?>


