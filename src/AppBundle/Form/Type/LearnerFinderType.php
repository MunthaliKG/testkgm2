<?php 
// src/AppBundle/Form/Type/SchoolFinderType.php
namespace AppBundle\Form\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

//this class build the form that is used to select a school using district name and school name
class LearnerFinderType extends AbstractType
{
	protected $learnerList; //dynamically generated schoolList that was used by the last ajax call
	
	function __construct($learnerList){
		$this->learnerList = $learnerList;
	}

	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		//add the form fields
		$builder
		->add('school', 'entity', array(
			'label' => 'school',
			'class' => 'AppBundle:School',
			'choice_label' => 'schoolName',
			'placeholder' => 'School Name',
			)
		)
		->add('learner', 'entity', array(
			'class' => 'AppBundle:Lwd',
			'choice_label' => 'learnerName',
			'choices' => $this->learnerList, 
			'constraints' => array(new NotBlank()),
			'placeholder' => 'Learner Name',
		)); 
	}
	public function getName()
	{
		return 'learnerfinder';
	}
}
?>

