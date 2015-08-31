<?php 
// src/AppBundle/Form/Type/TransferType.php
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

//this class builds the form that is used to transfer a learner from one school to another
class TransferType extends AbstractType
{
	private $districts = array();
	private $schools = array();
        private $learners = array();
	private $isDeletable;
	private $name;

	function __construct($districts, $schools){
                //$learners, $schools, $districts, $name = "", $isDeletable = true){
		//populate the districts, schools and learners for the district and school chosen
	        foreach($schools as $key => $school){
			$this->schools[$school['emiscode']] = $school['emiscode'].': '.$school['emiscode'];
		}
                //foreach($learners as $key => $learner){
		//	$this->learners[$learner['idlwd']] = $learner['idlwd'].': '.$learner['first_name'].' '.$learner['first_name'];
		//}                 
		$this->districts = $districts;
		//$this->isDeletable = $isDeletable;
		//$this->name = $name;
	}
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		//add the form fields	
		$builder
		//->add('iddisability_2','hidden')
		->add('ditrict','choice', array(
			'placeholder' => 'District learner is from',
			'label' => 'District Learner is from',
			'choices' => $this->districts,
			//'empty_data' => '',
			'constraints' => array(new NotBlank()),
                    //array(($this->name == "")? new NotBlank(): new Type('\d+')),
			)
		)
		->add('emiscode','choice', array(
			'label' => 'School Learner is from',
			'choices' => $this->schools,
			'required' => false,
			'empty_data' => '',
			)
		)
		
                        /*->add('idlwd','choice', array(
			'label' => 'Learner',
			'choices' => $this->learners,
			'required' => false,
			'empty_data' => '',
			)
		)
		->add('year','date', array(
			'label' => 'Year Transfering',
			'widget' => 'single_text',
			'format' => 'yyyy',
			'constraints' => array(new NotBlank()),
			'attr' => array('class'=>'datepicker','data-date-format'=>'dd-mm-yyyy'),
			)
		)
                 * */              
		->add('save','submit', array('label' => 'save'));
                /*
		if($this->isDeletable){
			$builder->add('remove','submit', array('label' => 'remove'));
		}*/
	}
	public function getName()
	{
		//if($this->name != ""){
		//	return $this->name;
		//}
		return 'learner_disability';
	}
}
?>