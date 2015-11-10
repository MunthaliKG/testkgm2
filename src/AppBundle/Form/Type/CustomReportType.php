<?php 
// src/AppBundle/Form/Type/SchoolFinderType.php
namespace AppBundle\Controller;
namespace AppBundle\Form\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;

//this class build the form that is used to specify custom reports parameters
class CustomReportType extends AbstractType
{
    //private $needs;    

    //public function __construct($needs)
    //{
    //    $this->needs = $needs;        
    //}
    public function buildForm(FormBuilderInterface $builder, array $options){
        //$needs = $this->needs;    
        //sub-reports to include in the report
        $reports = [0=>"SN Learners' details",1=>"SN Teachers details"];
        $standards = [1=>"Std 1", 9=>"Std 1 TO...", 2=>"Std 2", 3=>"Std 3", 4=>"Std 4", 5=>"Std 5", 6=>"Std 6", 7=>"Std 7", 8=>"Std 8"];
        $standard_range = [1=>"Std 1", 2=>"Std 2", 3=>"Std 3", 4=>"Std 4", 5=>"Std 5", 6=>"Std 6", 7=>"Std 7", 8=>"Std 8"];
        $stdrange = [0=>"One Standard", 1=>"Between"];
        //available formats for the report
        $formats = ['html'=>'html', 'pdf'=>'pdf', 'excel'=>'excel'];
        
        $builder
        ->add('reports','choice', array(
				'label' => 'Include',
				'expanded' => true,
				'multiple' => true,
				'choices'=> $reports,
				'constraints' => array(new NotBlank(["message"=>"Please select atleast one option"])),
				))
                        ->add('standard','choice', array(
				'label' => 'Standard',
				'expanded' => false,
				'multiple' => false,
				'choices'=> $standards,
				//'constraints' => array(new NotBlank(["message"=>"Please select atleast one option"])),
				))
                        
                         ->add('stdrange','choice', array(
				'label' => 'Standard Range',
                                'placeholder' => 'Choose standard',
				'expanded' => false,
				'multiple' => false,
				'choices'=> $standard_range,
				//'constraints' => array(new NotBlank(["message"=>"Please select atleast one option"])),
				))
			->add('format','choice', array(
				'label' => 'Format',
				'expanded' => true,
				'multiple' => false,
				'choices'=> $formats,
				'data' => 0,
				'constraints' => array(new NotBlank(["message"=>"Please select a format"])),
				))
			->add('produce','submit', array(
                            'label' => "Produce report")
                        )
                        ->addEventListener(
            FormEvents::PRE_SUBMIT,
            function (FormEvent $event){
                $form = $event->getForm();
                $data = $event->getData();

                if($data['standard'] == 'Std 1 TO...'){
                	$form->add('stdrange','choice', array(
				'label' => 'Standard Range',
                                'placeholder' => 'Choose standard',
				'expanded' => false,
				'multiple' => false,
				'choices'=> $standard_range,
				//'constraints' => array(new NotBlank(["message"=>"Please select atleast one option"])),
				));
                }
            }
        );
//        if ($options['use_range']) {
//            $builder->add('standard2','choice', array(
//				'label' => 'Standard',
//				'expanded' => false,
//				'multiple' => false,
//				'choices'=> $standards,
//				//'constraints' => array(new NotBlank(["message"=>"Please select atleast one option"])),
//				));
//        }else {
//            $builder->add('standard2','choice', array(
//                                'required' => false,
//				'label' => 'Standard',
//				'expanded' => false,
//				'multiple' => false,
//				'choices'=> $standards,
//				//'constraints' => array(new NotBlank(["message"=>"Please select atleast one option"])),
//				));
//        }
    }
    public function getName()
    {
        return 'customReport';
    }
   
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'use_range' => true
        ));
    }

}
?>


