<?php 
// src/AppBundle/Form/Type/LearnerDisabilityType.php
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

//this class builds the form that is used to add/edit a learner's disability/special need
class LearnerDisabilityType extends AbstractType
{
	private $levels  = array();
	private $disabilities = array();

	function __construct($disabilities, $levels = array()){
		//populate the disabilities array and the levels for the currently chosen disability
		foreach($disabilities as $key => $disability){
			$this->disabilities[$disability['iddisability']] = $disability['disability_name'];
		}
		foreach($levels as $key => $level){
			$this->levels[$level['idlevel']] = $level['level_name'];
		}
	}
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		//add the form fields	
		$builder
		->add('disability_name','choice', array(
			'placeholder' => 'disability/special need',
			'label' => 'Disability/Special Need',
			'choices' => $this->disabilities,
			'constraints' => array(new NotBlank()),
			)
		)
		->add('level_name','choice', array(
			'label' => 'Level',
			'choices' => $this->levels,
			'required' => false,
			'constraints' => array(new NotBlank()),
			)
		)
		->add('case_description','textarea', array(
			'label' => 'Description',
			'required' => false,)
		);
	}
	public function getName()
	{
		return 'learner_disability';
	}
}
?>