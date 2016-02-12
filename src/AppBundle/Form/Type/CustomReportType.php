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
    public function buildForm(FormBuilderInterface $builder, array $options){            
        //sub-reports to include in the report
        $enrollments = [0=>"Class & Gender", 1=>"Disability Category & Gender", 2=>"Disability & Gender"];
        $reports = [0=>"SN Learners' details",1=>"SN Teachers details"];
        //available formats for the report
        $formats = ['html'=>'html', 'pdf'=>'pdf', 'excel'=>'excel'];
        
        $builder
        ->add('reports','choice', array(
                        'label' => 'Include',
                        'expanded' => true,
                        'multiple' => true,
                        'choices'=> $reports,
                        //'constraints' => array(new NotBlank(["message"=>"Please select atleast one option"])),
                        ))
        ->add('enrollments','choice', array(
				'label' => 'Enrollement by:',
				'expanded' => true,
				'multiple' => true,
				'choices'=> $enrollments,
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
                        );
    }
    public function getName()
    {
        return 'customReport';
    }
}
?>


