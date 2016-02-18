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
			$session = $request->getSession();
			//echo $request->attributes->get('emisCode'); exit;
			if($user->hasAccess(1,$emisCode, $connection)){ /*check if this user has access to
				the 'school' with the emis code '$emisCode'*/
				$schoolAccess = array();
				if($session->has('school_access')){
					$schoolAccess = $session->get('school_access');
				}
				$schoolAccess[] = $emisCode;
				$session->set('school_access', $schoolAccess);
//				$this->addFlash('accessed', 'true');
//				//echo print_r($session->getFlashBag()->get('accessed'));exit;

//				$session->remove('school_name');
//		        $session->remove('emis_code');
//		        $session->remove('schoolInfo');

				// $router = $this->get('router');
				// $result = $router->match('/school/500595');
				// echo print_r($result);exit;
				return $this->redirect($request->getRequestUri(), 301);
			}
			else{
				$this->addFlash('errorMsg', 'You do not have access to that school');
				return $this->redirectToRoute('school_return', array(), 301);
			}
		}

		public function checkZoneAccessAction($zemisCode, Request $request){

			$user = $this->get('security.token_storage')->getToken()->getUser();
			$connection = $this->get('database_connection');
			$session = $request->getSession();
			//echo $request->attributes->get('emisCode'); exit;
			if($user->hasAccess(2,$zemisCode, $connection)){ /*check if this user has access to
				the zone with the code '$zemisCode'*/
				$zoneAccess = array();
				if($session->has('zone_access')){
					$zoneAccess = $session->get('zone_access');
				}
				$zoneAccess[] = $zemisCode;
				$session->set('zone_access', $zoneAccess);

				// $router = $this->get('router');
				// $result = $router->match('/school/500595');
				// echo print_r($result);exit;
				return $this->redirect($request->getRequestUri(), 301);
			}
			else{
				$this->addFlash('errorMsg', 'You do not have access to that zone');
				return $this->redirectToRoute('zone', array(), 301);
			}
		}

        public function checkDistrictAccessAction($demisCode, Request $request){

            $user = $this->get('security.token_storage')->getToken()->getUser();
            $connection = $this->get('database_connection');
            $session = $request->getSession();
            if($user->hasAccess(3,$demisCode, $connection)){ /*check if this user has access to
				the district with the code '$demisCode'*/
                $districtAccess = array();
                if($session->has('district_access')){
                    $districtAccess = $session->get('district_access');
                }
                $districtAccess[] = $demisCode;
                $session->set('district_access', $districtAccess);

                // $router = $this->get('router');
                // $result = $router->match('/school/500595');
                // echo print_r($result);exit;
                return $this->redirect($request->getRequestUri(), 301);
            }
            else{
                $this->addFlash('errorMsg', 'You do not have access to that district');
                return $this->redirectToRoute('district_return', array(), 301);
            }
        }

        public function checkNationalAccessAction(Request $request){

            $user = $this->get('security.token_storage')->getToken()->getUser();
//            $connection = $this->get('database_connection');
            $session = $request->getSession();
            if(!$session->has('national_access')){
                if($user->hasAccess(4, 4, null)){ /*check if this user has national access*/
                    $session->set('national_access', 'granted');
                    return $this->redirect($request->getRequestUri(), 301);
                }
                else{
                    $session->set('national_access', 'denied');
                    $this->addFlash('errorMsg', 'Your access rights do not allow you to access the national level');
                    return $this->redirectToRoute('national_denied', array(), 301);
                }
            }
            else{
                $this->addFlash('errorMsg', 'Your access rights do not permit you to access the national level');
                return $this->redirectToRoute('national_denied', array(), 301);
            }

        }
	}

?>