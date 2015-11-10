<?php 
// src/AppBundle/Form/Type/AddUserType.php
namespace AppBundle\Form\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Validator\Constraints as Assert;

//this class build the form that is used to add a new user
class AddUserType extends AbstractType
{
	private $accessDomains;
	private $isSuperAdmin; //used to check whether the admin logged in is a super admin
	private $edit;
	public function __construct($accessDomains, $isSuperAdmin, $edit = false){
		$this->accessDomains = $accessDomains;
		$this->isSuperAdmin = $isSuperAdmin;
		$this->edit = $edit;
	}
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		//add the roles that can be assigned by this administrator;
		$roles = array('ROLE_USER' => 'User');
		if($this->isSuperAdmin){
			$roles['ROLE_ADMIN'] = 'Administrator';
			$roles['ROLE_SUPER_ADMIN'] = 'Super Administrator';
		}
		//add the form fields
		$builder
		->add('id','hidden',array())
		->add('first_name', 'text', array(
			'label' => 'First Name',
			'constraints' => array(new Assert\NotBlank()),
			)
		)
		->add('last_name', 'text', array(
			'label' => 'Last Name',
			'constraints' => array(new Assert\NotBlank()),
			)
		);
		if(!$this->edit){
			$builder
			->add('username', 'text', array(
				'label' => 'Username',
				'constraints' => array(new Assert\NotBlank()),
				)
			)
			->add('email', 'email', array(
				'label' => 'Email',
				'constraints' => array(
					new Assert\NotBlank(),
					new Assert\Email(array('message'=>'Please enter a valid email'))
					),
				)
			);
		}
		
		$builder
		->add('roles', 'choice', array(
			'label' => 'User Role',
			'placeholder' => '--Select user role--',
			'choices' => $roles,
			'constraints' => array(new Assert\NotBlank()),
			'label_attr' => array('title' => "what is this user's main role in the system?" )
			)			
		)
		->add('access_level', 'choice', array(
			'label' => 'Access Level',
			'placeholder' => '--Select access level--',
			'choices' => array(1 => 'school', 2 => 'zone', 3 => 'district', 4 => 'national'),
			'constraints' => array(new Assert\NotBlank()),
			'label_attr' => array('title' => "at what level is this user allowed to interact with the system?" )
			)
		)
		->add('access_domain', 'choice', array(
			'label' => 'Access Domain',
			'placeholder' => '--Select access domain--',
			'choices' => $this->accessDomains, 
			'constraints' => array(new Assert\NotBlank()),
			'label_attr' => array('title' => "what specific school, zone or district is this user allowed to access?" )
			)
		)
		->add('allowed_actions', 'choice', array(
			'label' => 'Allowed actions',
			'placeholder' => '--Select allowed action--',
			'choices' => array(1 => 'view', 2 => 'edit'),
			'constraints' => array(new Assert\NotBlank()),
			'label_attr' => array('title' => "what actions is the user allowed to do with the system's data?" )
			)
		)
		->add('add', 'submit', array(
			'label' => 'Save'
			)
		)->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
			$user = $event->getData();
			$form = $event->getForm();

			if (!$user) {
				return;
			}
			
			//remove different fields based on the value of other fields
			if($user['roles'] == 'ROLE_SUPER_ADMIN' || $user['access_level'] == '4'){
				$form->remove('access_domain');
			}

			if($user['roles'] == 'ROLE_SUPER_ADMIN' || $user['roles'] == 'ROLE_ADMIN'){
				$form->remove('allowed_actions');
			}

			if($user['roles'] == 'ROLE_SUPER_ADMIN'){
				$form->remove('access_level');
			}
			
		});
	}
	public function getName()
	{
		return 'adduser';
	}
}
?>