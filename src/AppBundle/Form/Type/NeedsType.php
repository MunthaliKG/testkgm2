<?php 
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

//this class builds the form for filling out the needs that are available for a particular disability
class NeedsType extends AbstractType{
	private $needs;
	private $name;

	function __construct($needs = array(), $name = ""){
		foreach($needs as $key => $need){
			$this->needs[$need['idneed']] = $need['needname'];
		}
		$this->name = $name;
	}
	public function buildForm(FormBuilderInterface $builder, array $options){
		$builder
		->add('needs','choice',array(
			'label'=>'',
			'constraints' => array(new NotBlank(array('message'=>'Please check at least one item'))),
			'expanded' => true,
			'multiple' => true,
			'choices' => $this->needs,
			)
		)
		->add('save','submit', array(
			'label' => 'save')
		);
	}

	public function getName()
	{
		if($this->name != ""){
			return 'learner_needs_'.$this->name;
		}
		return 'learner_needs';
	}
}

?>