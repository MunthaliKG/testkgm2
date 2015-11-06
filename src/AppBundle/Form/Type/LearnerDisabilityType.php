<?php 
// src/AppBundle/Form/Type/LearnerDisabilityType.php
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Range;

//this class builds the form that is used to add/edit a learner's disability/special need
class LearnerDisabilityType extends AbstractType
{
	private $levels ;
	private $disabilities = array();
	private $isDeletable;
	private $name;
	private $needs;

	function __construct($disabilities, $levels = array(), $name = "", $needs = array(), $isDeletable = true){
		//populate the disabilities array and the levels for the currently chosen disability
		foreach($disabilities as $key => $disability){
			$this->disabilities[$disability['iddisability']] = $disability['disability_name'];
		}
		foreach($levels as $key => $level){
			$this->levels[$level['idlevel']] = $level['level_name'];
		}
		foreach($needs as $key => $need){
			$this->needs[$need['idneed']] = $need['needname'];
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
			'expanded' => false,
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
			'label' => 'Identified on (yyyy-mm-dd)',
			'format' => 'yyyy-MM-dd',
			'years' => range(date('Y')-30, date('Y')),
			'constraints' => array(new NotBlank(), new Range(array(
				'min'=>'-30 years',
				'max'=>'now', 
				'maxMessage'=>'This date should be '.date('M j, Y').' or less'
				))),
			'attr' => array(
				'data-date-format'=>'dd-mm-yyyy',
				'max' => date('Y-m-d')
				),
			)
		)
		->add('save','submit', array('label' => 'save'));

		if($this->isDeletable){
			$builder->add('remove','submit', array('label' => 'remove'))
			->add('needs','choice',array(
				'label'=>'',
				'expanded' => true,
				'multiple' => true,
				'choices' => $this->needs,
				'required' => true,
				)
			);
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