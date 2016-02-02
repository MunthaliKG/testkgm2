<?php 
// src/AppBundle/Form/Type/LearnerExitCollectionType.php
namespace AppBundle\Form\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\FormEvents;
use AppBundle\Form\Type\LearnerExitType;
//this class build the form that is used to select a school using district name and school name
use Symfony\Component\Form\FormEvent;
class LearnerExitCollectionType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		//add the form fields
		for($i = 1; $i <= 6; $i++){
			$builder
		    ->add('learner_exit'.$i, new LearnerExitType() , array(
		    	'required'=>false
		    	));
		}
		
	}
	public function getName()
	{
		return 'learner_exit_coll';
	}
}
?>