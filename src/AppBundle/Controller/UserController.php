<?php
/*this is the controller for the user management admin functions
*it controls all links starting with admin/users
*/

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\User;
use AppBundle\Form\Type\AddUserType;


class UserController extends Controller{
	/**
	 *@Route("/admin/user/{username}/{action}", name="admin_change_access",requirements={"action":"enable|disable"})
	 */
	public function changeAccessAction($username, $action, Request $request){
		$um = $this->get('fos_user.user_manager');
		$user = $um->findUserByUsername($username);

		if($action == 'enable'){
			$user->setEnabled(true);
		}
		elseif($action == 'disable'){
			$user->setEnabled(false);
		}

		$um->updateUser($user);

		return $this->redirectToRoute('admin_users_main', array(), 301);

	}
	/**
	 *@Route("/admin/user/add", name="admin_add_user")
	 */
	public function addUserAction(Request $request){
		$connection = $this->get('database_connection');
		$districts = $connection->fetchAll('SELECT iddistrict, district_name FROM district');
		if($request->getSession()->getFlashBag()->has('added')){
			$request->getSession()->getFlashBag()->get('added');
			return $this->render('admin/usermanagement/user_added.html.twig');
		}
		$accessDomains = array();
		if($request->getSession()->getFlashBag()->has('access_domains')){
			$accessDomains = $request->getSession()->getFlashBag()->get('access_domains');
		}
		$isSuperAdmin = $this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN');
		$form = $this->createForm(new AddUserType($accessDomains, $isSuperAdmin));

		$form->handleRequest($request);
		if($form->isValid()){
			$formData = $form->getData();
			$um = $this->get('fos_user.user_manager');
			$user = $um->createUser();
			$user->setUsername($formData['username']);
			$user->setFirstName($formData['first_name']);
			$user->setLastName($formData['last_name']);
			$user->setEmail($formData['email']);

			if($formData['roles'] == 'ROLE_USER'){
				$formData['roles'] = '';
			}
			$user->setRoles(array($formData['roles']));

			if($formData['roles'] != 'ROLE_SUPER_ADMIN'){
				$user->setAccessLevel($formData['access_level']);
				$user->setAccessDomain($formData['access_domain']);
				$user->setAllowedActions($formData['allowed_actions']);
			}
			else{
				$user->setAccessLevel(4);
				$user->setAccessDomain(0);
				$user->setAllowedActions(2);
			}
			
			//set default password for the user
			$plainPassword = $formData['ulast_name'].$formData['ufirst_name'].$formData['username'];
			$encoder = $this->container->get('security.password_encoder');
			$encoded = $encoder->encodePassword($user, $plainPassword);
			$user->setPassword($encoded);

			$um->updateUser($user);
			$this->addFlash('added', true);
			return $this->redirectToRoute('admin_add_user', array(), 301);
		}

		return $this->render('admin/usermanagement/add_user.html.twig', array(
			'form' => $form->createView(),
			'districts' => $districts
			)
		);
	}
	/**
	 *@Route("/admin/user/{username}/edit", name="admin_edit_user")
	 */
	public function editUserAction($username, Request $request){
		$connection = $this->get('database_connection');
		$districts = $connection->fetchAll('SELECT iddistrict, district_name FROM district');

		$userArray = $connection->fetchAssoc('SELECT ufirst_name, ulast_name, roles, access_level, 
		    access_domain, allowed_actions FROM fos_user WHERE username = ?', [$username]);

		
		//get the list of access domains to populate the select list
		$accessDomains = array();
		if($request->getSession()->getFlashBag()->has('access_domains')){
			$accessDomains = $request->getSession()->getFlashBag()->get('access_domains');
		}
		else{
			if($userArray['access_level'] != 4){
				$name = '';
				$id = '';
				switch($userArray['access_level']){
					case 1: $name = 'school';
					break;
					case 2: $name = 'zone';
					break;
					case 3: $name = 'district';
					break;
				}
				$id = ($userArray['access_level'] == 1)? 'emiscode' : 'id'.$name;
				$ad = $connection->fetchAssoc('SELECT '.$id.','.$name.'_name FROM '.$name.' WHERE '.$id.' = ?', [$userArray['access_domain']]);
				$accessDomains = [$ad[$id] => $ad[$name.'_name']];
			}
		}
		$isSuperAdmin = $this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN');

		$userArray['first_name'] = $userArray['ufirst_name'];
		$userArray['last_name'] = $userArray['ulast_name'];	
		unset($userArray['ufirst_name']);
		unset($userArray['ulast_name']);
		$userArray['roles'] =  unserialize($userArray['roles']);
		if(empty($userArray['roles'])){
			$userArray['roles'] = 'ROLE_USER';
		}
		else{
			$userArray['roles'] = $userArray['roles'][0];
		}
		$form = $this->createForm(new AddUserType($accessDomains, $isSuperAdmin, true), $userArray);

		$form->handleRequest($request);
		if($form->isValid()){
			$formData = $form->getData();
			$um = $this->get('fos_user.user_manager');
			$user = $um->findUserByUsername($username);

			if($user){
				$user->setFirstName($formData['first_name']);
				$user->setLastName($formData['last_name']);
				$user->setRoles(array($formData['roles']));

				if($formData['roles'] != 'ROLE_SUPER_ADMIN'){
					$user->setAccessLevel($formData['access_level']);
					$user->setAccessDomain($formData['access_domain']);
					$user->setAllowedActions($formData['allowed_actions']);
				}
				else{
					$user->setAccessLevel(4);
					$user->setAccessDomain(0);
					$user->setAllowedActions(2);
				}

				$um->updateUser($user);
				$this->addFlash('profileEditedMessage', $username.'\'s profile updated successfully');
				return $this->redirectToRoute('admin_users_main', array(), 301);
			}
			
		}
		
		return $this->render('admin/usermanagement/add_user.html.twig', array(
			'form' => $form->createView(),
			'districts' => $districts
			)
		);
	}
	/**
	 *@Route("/admin/access_domain/{level}/{district}", name="populate_access_domain", requirements={"level":"\d+","district":"\d+"}, options={"expose":true})
	 */
	public function populateAccessDomainAction($level, $district, Request $request){
		$connection = $this->get('database_connection');
		$params = array();
		switch($level){
			case 1: $accessLevel = "school";
					$whereClause = "NATURAL JOIN district WHERE iddistrict = ?";
					$params[] = $district;
					break;
			case 2: $accessLevel = "zone";
					$whereClause = "NATURAL JOIN district WHERE iddistrict = ?";
					$params[] = $district;
					break;
			default: $accessLevel = "district";
					$whereClause = "";
					break;
		}
		$accessDomains = $connection->fetchAll('SELECT * FROM '.$accessLevel.' '.$whereClause, $params);
		$html = "<option value=\"\">--Select access domain--</option>";
		$id = ($level == 2 || $level == 3)? 'id'.$accessLevel : 'emiscode';
		foreach($accessDomains as $accessDomain){
			$html .= '<option value="'.$accessDomain[$id].'">'.$accessDomain[$accessLevel.'_name'].'</option>';
		}
		$newAccessDomains = array();
		foreach($accessDomains as $domain){
				$newAccessDomains[$domain[$id]] = $domain[$accessLevel.'_name'];
		}
		$request->getSession()->getFlashBag()->set('access_domains', $newAccessDomains);
		return new Response($html);
	}
}
?>