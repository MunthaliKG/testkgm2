<?php 
// src/AppBundle/Form/Type/SchoolFinderType.php
namespace AppBundle\Form\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

//this class build the form that is used to select a school using district name and school name
class SchoolFinderType extends AbstractType
{
	protected $schoolList; //dynamically generated schoolList that was used by the last ajax call
	
	function __construct($schoolList){
		$this->schoolList = $schoolList;
	}

	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		//add the form fields
		$builder
		->add('district', 'entity', array(
			'label' => 'district',
			'class' => 'AppBundle:District',
			'choice_label' => 'districtName',
			'placeholder' => 'District Name',
			)
		)
		->add('school', 'entity', array(
			'class' => 'AppBundle:School',
			'choice_label' => 'schoolName',
			'choices' => $this->schoolList, 
			'constraints' => array(new NotBlank()),
			'placeholder' => 'School Name',
		)); 
	}
	public function getName()
	{
		return 'schoolfinder';
	}
}
?>