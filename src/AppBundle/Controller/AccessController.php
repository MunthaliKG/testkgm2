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

			//$language = new ExpressionLanguage();
			//echo 'reached'; exit;
			$user = $this->get('security.token_storage')->getToken()->getUser();
			$connection = $this->get('database_connection');
			$session = $request->getSession();

			if($user->hasAccess(1,$emisCode, $connection)){ /*check if this user has access to
				the 'school' with the emis code '$emisCode'*/
				// if($session->has('school_access')){
				// 	$schoolAccess = $session->get('school_access');
				// }
				// $schoolAccess[] = $emisCode;
				// $session->set('school_access', $schoolAccess)
				$this->addFlash('accessed', true);
				$session->set('school_access', $emisCode);

				$session->remove('school_name');
		        $session->remove('emis_code');
		        $session->remove('schoolInfo'); 
		        $session->invalidate();
				$session->save();
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