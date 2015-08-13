<?php 
// src/AppBundle/Form/Type/LearnerDisabilityType.php
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

//this class builds the form that is used to add/edit a learner's disability/special need
class LearnerDisabilityType extends AbstractType
{
	private $levels ;
	private $disabilities = array();
	private $isDeletable;
	private $name;

	function __construct($disabilities, $levels = array(), $name = "", $isDeletable = true){
		//populate the disabilities array and the levels for the currently chosen disability
		foreach($disabilities as $key => $disability){
			$this->disabilities[$disability['iddisability']] = $disability['disability_name'];
		}
		foreach($levels as $key => $level){
			$this->levels[$level['idlevel']] = $level['level_name'];
		}
		$this->isDeletable = $isDeletable;
		$this->name = $name;
	}
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		//add the form fields	
		$builder
		->add('iddisability_2','hidden')
		->add('iddisability','choice', array(
			'placeholder' => 'disability/special need',
			'label' => 'Disability/Special Need',
			'choices' => $this->disabilities,
			'empty_data' => '',
			'constraints' => array(($this->name == "")? new NotBlank(): new Type('\d+')),
			)
		)
		->add('idlevel','choice', array(
			'label' => 'Level',
			'choices' => $this->levels,
			'required' => false,
			'empty_data' => '',
			)
		)
		->add('case_description','textarea', array(
			'label' => 'Description',
			'required' => false,
			)
		)
		->add('identified_by','choice', array(
			'label' => 'Identified by',
			'expanded' => true,
			'multiple' => false,
			'constraints' => array(new NotBlank()),
			'choices' => array(
				'special needs teacher' => 'special needs teacher',
				'ordinary teacher' => 'ordinary teacher',
				'health personnel' => 'health personnel',
				'parents' => 'parents'),
			)
		)
		->add('identification_date','date', array(
			'label' => 'Identified on',
			'widget' => 'single_text',
			'format' => 'dd-MM-yyyy',
			'constraints' => array(new NotBlank()),
			'attr' => array('class'=>'datepicker','data-date-format'=>'dd-mm-yyyy'),
			)
		)
		->add('save','submit', array('label' => 'save'));

		if($this->isDeletable){
			$builder->add('remove','submit', array('label' => 'remove'));
		}
	}
	public function getName()
	{
		if($this->name != ""){
			return $this->name;
		}
		return 'learner_disability';
	}
}
?>