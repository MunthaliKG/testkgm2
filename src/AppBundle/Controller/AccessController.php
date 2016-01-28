<?php 
	/*this is a controller for controlling access to different areas of the system*/
	namespace AppBundle\Controller;

	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
	use Symfony\Component\HttpFoundation\Response;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

	class AccessController extends Controller{
		
		public function checkSchoolAccessAction($emisCode, Request $request){

			$user = $this->get('security.token_storage')->getToken()->getUser();
			$connection = $this->get('database_connection');
			$session = $this->getRequest()->getSession();
			//echo $request->attributes->get('emisCode'); exit;
			if($user->hasAccess(1,$emisCode, $connection)){ /*check if this user has access to
				the 'school' with the emis code '$emisCode'*/
				$schoolAccess = array();
				if($session->has('school_access')){
					$schoolAccess = $session->get('school_access');
				}
				$schoolAccess[] = $emisCode;
				$session->set('school_access', $schoolAccess);
				$this->addFlash('accessed', 'true');
				//echo print_r($session->getFlashBag()->get('accessed'));exit;

				$session->remove('school_name');
		        $session->remove('emis_code');
		        $session->remove('schoolInfo'); 

				// $router = $this->get('router');
				// $result = $router->match('/school/500595');
				// echo print_r($result);exit;
				return $this->redirect($request->getRequestUri(), 301);
			}
			else{
				$this->addFlash('errorMsg', 'You do not have access to that school');
				return $this->redirectToRoute('school', array(), 301);
			}
		}
	}

?>