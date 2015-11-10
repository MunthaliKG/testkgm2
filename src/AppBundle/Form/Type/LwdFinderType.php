<?php 
// src/AppBundle/Form/Type/LwdFinderType.php
namespace AppBundle\Form\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

//this class build the form that is used to select a school using district name and school name
class LwdFinderType extends AbstractType
{
	protected $schoolList; //dynamically generated schoolList that was used by the last ajax call
        protected $learnerList; //dynamically generated learnerList that was used by the last ajax call
        protected $schoolsTo;
                
	function __construct($schoolList, $learnerList, $schoolsTo){
		$this->schoolList = $schoolList;
                $this->learnerList = $learnerList;
                $this->schoolsTo = $schoolsTo;
	}

	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		//add the form fields
		$builder
		->add('district', 'entity', array(
			'label' => 'Learner to Transfer',
			'class' => 'AppBundle:District',
			'choice_label' => 'districtName',
			'placeholder' => 'District Name',
			)
		)
		->add('school', 'choice', array(			
			'choices' => $this->schoolList, 
			'constraints' => array(new NotBlank()),
			'placeholder' => 'School Name',
		))
                ->add('learner', 'choice', array(
			'choices' => $this->learnerList, 
			'constraints' => array(new NotBlank()),
			'placeholder' => 'Learner Name',
		))
                ->add('schoolTo', 'choice', array(
			'placeholder' => 'Choose School',
                       'label' => 'School to transfer Learner to',
                       'choices' => $this->schoolsTo,                
                        'expanded' => false,
                        'multiple' => false,
			'constraints' => array(new NotBlank()),
			)
		)
                ->add('std', 'choice', array(
                        'placeholder' => 'Choose standard',
			//'label' => 'Other Reason',
			'required' => false,
                        'choices' => array(
				1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6, 7 => 7, 8 => 8,
				),
			'constraints' => array(new NotBlank()),
			)
		)
                ->add('year', 'datetime', array(
                    'label' => 'Year',
                    'widget' => 'single_text',
                    'format' => 'yyyy',
                    'attr' => array('class'=>'datepicker','data-date-format'=>'yyyy '),
                    'constraints' => array(new NotBlank()),
                        )
                )
		->add('save', 'submit', array('label'=>'Save'));
	}
	public function getName()
	{
		return 'lwdfinder';
	}
}
?>
