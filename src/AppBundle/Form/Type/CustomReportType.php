<?php 
// src/AppBundle/Form/Type/SchoolFinderType.php
namespace AppBundle\Controller;
namespace AppBundle\Form\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

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
        $enrollments = [0=>"Class & Gender", 1=>"Disability Category & Gender", 2=>"Disability & Gender"];
//        $reports = [0=>"SN Learners' details",1=>"SN Teachers details"];
//        $standards = [1=>"Std 1", 9=>"Std 1 TO...", 2=>"Std 2", 3=>"Std 3", 4=>"Std 4", 5=>"Std 5", 6=>"Std 6", 7=>"Std 7", 8=>"Std 8"];
//        $standard_range = [1=>"Std 1", 2=>"Std 2", 3=>"Std 3", 4=>"Std 4", 5=>"Std 5", 6=>"Std 6", 7=>"Std 7", 8=>"Std 8"];
//        $stdrange = [0=>"One Standard", 1=>"Between"];
        //available formats for the report
        $formats = ['html'=>'html', 'pdf'=>'pdf', 'excel'=>'excel'];
        
        $builder
        ->add('enrollments','choice', array(
				'label' => 'Enrollement by:',
				'expanded' => true,
				'multiple' => true,
				'choices'=> $enrollments,
				'constraints' => array(new NotBlank(["message"=>"Please select atleast one option"])),
				))
//                        ->add('standard','choice', array(
//				'label' => 'Standard',
//				'expanded' => false,
//				'multiple' => false,
//				'choices'=> $standards,
//				//'constraints' => array(new NotBlank(["message"=>"Please select atleast one option"])),
//				))
//                        
//                         ->add('stdrange','choice', array(
//				'label' => 'Standard Range',
//                                'placeholder' => 'Choose standard',
//				'expanded' => false,
//				'multiple' => false,
//				'choices'=> $standard_range,
//				//'constraints' => array(new NotBlank(["message"=>"Please select atleast one option"])),
//				))
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
                        );
    }
    public function getName()
    {
        return 'customReport';
    }
}
?>


