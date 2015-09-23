<?php
/*this is the controller for the admin page
*it controls all links starting with admin/
*/

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\Type\UserFilterType;

class AdminController extends Controller{
	/**
	 *@Route("/admin", name="admin_main")
	 */
	public function adminMainAction(Request $request){
		return $this->render('admin/admin_main.html.twig');
	}
	/**
	 *@Route("/admin/users", name="admin_users_main")
	 */
	public function adminUsersMainAction(Request $request){
		$connection = $this->get('database_connection');
		$session = $this->container->get('session');
		$form = $this->createForm(new UserFilterType());
		$form->handleRequest($request);
		if($form->isValid()){
			if($form->get('clear')->isClicked()){
				$session->remove('fname_filter');
				$session->remove('lname_filter');
				$session->remove('show_disabled_filter');
			}
			else{
				$formData = $form->getData();
				if($formData['show_disabled'] != null){
					$session->set('show_disabled_filter', 1);
				}
				if(($firstName = $formData['first_name']) != null){
					$session->set('fname_filter', $firstName);
				}
				if(($lastName = $formData['last_name']) != null){
					$session->set('lname_filter', $lastName);
				}
			}
			$session->save();
		}
		$defaultData = array(); //form default data
		$whereClause = "";
		$params = array();

		//build the where clause
		$needsAnd = false;
		if($session->has('show_disabled_filter')){
			$needsAnd = true;
			$whereClause .= " WHERE enabled = 1";
			$defaultData['show_disabled'] = array(1);
		}
		if($session->has('fname_filter')){
			$firstName = $session->get('fname_filter');
			$whereClause .= ($needsAnd)? " AND" : " WHERE";
			$needsAnd = true;
			$whereClause .= " ufirst_name = ?";
			$params[] = $firstName;
			$defaultData['first_name'] = $firstName;
		}
		if($session->has('lname_filter')){
			$lastName = $session->get('lname_filter');
			$whereClause .= ($needsAnd)? " AND" : " WHERE";
			$needsAnd = true;
			$whereClause .= " ulast_name = ?";
			$params[] = $lastName;
			$defaultData['last_name'] = $lastName;
		}
		$form = $this->createForm(new UserFilterType(), $defaultData);
		$dataConverter = $this->get('data_converter');
		$users = $connection->fetchAll('SELECT id, username, ufirst_name, ulast_name, email, enabled, last_login, roles FROM fos_user'.$whereClause, $params);

		array_walk($users, function(&$row, $key, $dataConverter){
			$roleArray = unserialize($row['roles']);
			if(!empty($roleArray)){
				$row['roles'] = $dataConverter->convertToCommaString($roleArray, true);
			}
			else{
				$row['roles'] = 'ROLE_USER';
			}
			
		}, $dataConverter);

		$userData = array();
		$userData['numUsers'] = count($users);
		$userData['numDisabled'] = $dataConverter->countArray($users, 'enabled', 0);

		$paginator = $this->get('knp_paginator');
		$userPagination = $paginator->paginate($users, $request->query->getInt('page',1), 10);
		return $this->render('admin/usermanagement/admin_users_main.html.twig', 
			array('userData'=>$userData,
				'userPagination' => $userPagination,
				'filterForm' => $form->createView()
				)
			);
	}
	/**
	 *@Route("/admin/settings", name="admin_settings_main")
	 */
	public function adminSettingsMainAction(Request $request){
		return $this->render('admin/settings/admin_settings_main.html.twig');
	}
}
?>