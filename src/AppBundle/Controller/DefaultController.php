<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Form\Type\SchoolFinderType;
use AppBundle\Entity\User;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        return $this->redirectToRoute('school', array(), 301);
    }
    /**
     * @Route("/schoollist_ajax", name="school_ajax", condition="request.isXmlHttpRequest()",options={"expose"=true})
     */
    public function listSchoolsAction(Request $request){//this method will only be called through ajax
        $id = $request->query->get('id');
        $connection = $this->get('database_connection');
        $schools = $this->getDoctrine()->getRepository('AppBundle:School')->findByIddistrict($id);
        //$schools = $connection->fetchAll("SELECT DISTINCT emiscode, school_name 
            //FROM school WHERE iddistrict = ?", array($id));
        /*add the list of schools to the session
        The reason we want to do this is because select lists populated using ajax always cause $form->isValid()
         to return false. Therefore, we need to change the 'choices' attribute of this element in the SchoolFinderType
         form class to include the new list.
        since we are using a FlashBag, this session variable will be cleared after the next request
        */
        $this->get('session')->getFlashBag()->set('schoolList', $schools);
        return $this->render('school/schoollist.html.twig', array('schools'=>$schools));
    }
    /**
     * @Route("/school_return", name="school_return")
     */
    public function schoolReturnAction(Request $request){
        $session = $request->getSession();
        $session->remove('school_name');
        $session->remove('emiscode');
        $session->remove('schoolInfo'); 
        $session->save();

        return $this->redirectToRoute('school', array(), 301);
    }
    /**
     * @Route("/school", name="school")
     */
    public function schoolAction(Request $request){
        $session = $request->getSession();
        if($session->has('emiscode')){
            return $this->redirectToRoute('school_main', array('emisCode'=>$session->get('emiscode')), 301);
        }
        $error = null;
        if($request->getSession()->getFlashBag()->has('errorMsg')){
            $error = $request->getSession()->getFlashBag()->get('errorMsg');
        }
        return $this->render('school/school.html.twig', array('error'=>$error[0]));
    }
    /**
     * @Route("/findSchoolForm", name="find_school_form")
     */
    public function schoolSelectFormAction(Request $request){
        $schoolFinderForms = $this->container->get('school_finder');
        $formAction = $this->generateUrl('find_school_form');
        $schoolFinderForms->createForms($formAction);
        $schoolFinderForms->processForms();
        
        if($schoolFinderForms->areValid()){
            if($schoolFinderForms->getError() != null){
                $this->addFlash('errorMsg', $schoolFinderForms->getError());
                return $this->redirectToRoute('school');
            }
            else{
                return $this->redirectToRoute('school_main',array('emisCode'=>$schoolFinderForms->getSchoolId()), 301);
            }
            
        }
        $schoolName = "";
        if($request->getSession()->has('emiscode')){
            $schoolName = $request->getSession()->get('school_name');
        }
        return $this->render('school/findschoolform.html.twig',
                             array('form2' => $schoolFinderForms->getView2(),
                                    'form1' => $schoolFinderForms->getView1(),
                                    'error'=>$schoolFinderForms->getError(),
                                    'schoolName' => $schoolName,
                                    )                                   
                             );
    }
    /**
     * @Route("/zone", name="zone_return")
     */
    public function zoneReturnAction(Request $request){      
        return $this->render('zone/zone.html.twig');
    }
    /**
     * @Route("/zone", name="zone")
     */
    public function zoneAction(Request $request){
        $session = $request->getSession();
        if($session->has('idzone')){
            return $this->redirectToRoute('zone_main', array('idzone'=>$session->get('idzone')), 301);
        }
        return $this->render('zone/zone.html.twig');
    }
    /**
     * @Route("/findZoneForm", name="find_zone_form")
     */
    public function zoneSelectFormAction(Request $request){
        $zoneFinderForms = $this->container->get('zone_finder');
        $formAction = $this->generateUrl('find_zone_form');
        $zoneFinderForms->createForms($formAction);
        $zoneFinderForms->processForms();
        
        if($zoneFinderForms->areValid()){
            return $this->redirectToRoute('zone_main',array('idzone'=>$zoneFinderForms->getZoneId()), 301);
        }
        $zoneName = "";
        if($request->getSession()->has('idzone')){
            $zoneName = $request->getSession()->get('zone_name');
        }
        return $this->render('zone/findzoneform.html.twig',
                             array( 'form1' => $zoneFinderForms->getView1(),
                                    'error'=>$zoneFinderForms->getError(),
                                    'zoneName' => $zoneName,
                                    )                                   
                             );
    }
    /**
     * @Route("/findLearnerForm", name="find_lwd_form")
     */
    public function learnerSelectFormAction(Request $request){
        $learnerFinderForms = $this->container->get('learner_finder');
        $formAction = $this->generateUrl('find_lwd_form');
        $learnerFinderForms->createForms($formAction);
        $learnerFinderForms->processForms();
        
        if($learnerFinderForms->areValid()){
            return $this->redirectToRoute('district_transfer',array('iddistrict'=>$session->get('iddistrict'),'learnerId'=>$learnerFinderForms->getLearnerId()), 301);
        }
        $learnerName = "";
        if($request->getSession()->has('idzone')){
            $learnerName = $request->getSession()->get('learner_name');
        }
        return $this->render('school/learners/findlwdform.html.twig',
                             array( 'form2' => $learnerFinderForms->getView1(),
                                    'error'=>$learnerFinderForms->getError(),
                                    'learnerName' => $learnerName,
                                    )                                   
                             );
    }
    /**
     * @Route("/zonelist_ajax", name="zone_ajax", condition="request.isXmlHttpRequest()",options={"expose"=true})
     */
    public function listZonesAction(Request $request){//this method will only be called through ajax
        $id = $request->query->get('id');
        $zones = $this->getDoctrine()->getRepository('AppBundle:Zone')->findBy(array('districtdistrict'=>$id));

        /*add the list of schools to the session
        The reason we want to do this is because select lists populated using ajax always cause $form->isValid()
         to return false. Therefore, we need to change the 'choices' attribute of this element in the SchoolFinderType
         form class to include the new list.
        since we are using a FlashBag, this session variable will be cleared after the next request
        */
        $this->get('session')->getFlashBag()->set('zoneList', $zones);
        return $this->render('zone/zonelist.html.twig', array('zones'=>$zones));
    }
    /**
     * @Route("/learnerlist_ajax", name="learner_ajax", condition="request.isXmlHttpRequest()",options={"expose"=true})
     */
    public function listLearnersAction(Request $request){//this method will only be called through ajax
        $emis = $request->query->get('id');
        $connection = $this->get('database_connection');
    	//$lwd = $connection->fetchAll("SELECT first_name, last_name FROM lwd 
    	//				WHERE idlwd = ?", array($learners.idlwd));
        $learners = $connection->fetchAll("SELECT DISTINCT idlwd, first_name, last_name 
            FROM lwd NATURAL JOIN lwd_belongs_to_school WHERE emiscode = ?", array($emis));
        //$this->getDoctrine()->getRepository('AppBundle:LwdBelongsToSchool')->findBy(array('emiscode'=>$emis));

        /*add the list of schools to the session
        The reason we want to do this is because select lists populated using ajax always cause $form->isValid()
         to return false. Therefore, we need to change the 'choices' attribute of this element in the SchoolFinderType
         form class to include the new list.
        since we are using a FlashBag, this session variable will be cleared after the next request
        */
        $this->get('session')->getFlashBag()->set('learnerList', $learners);                
        return $this->render('school/learners/learnerlist.html.twig', array('learners'=>$learners));
    }
    /**
     * @Route("/district", name="district")
     */
    public function districtAction(Request $request){
        $session = $request->getSession();
        if($session->has('iddistrict')){
            return $this->redirectToRoute('district_main', array('iddistrict'=>$session->get('iddistrict')), 301);
        }   
        return $this->render('district/district.html.twig');
    }
     /**
     * @Route("/findDistrictForm", name="find_district_form")
     */
    public function districtSelectFormAction(Request $request){
        $districtFinderForms = $this->container->get('district_finder');
        $formAction = $this->generateUrl('find_district_form');
        $districtFinderForms->createForms($formAction);
        $districtFinderForms->processForms();
        
        if($districtFinderForms->areValid()){
            return $this->redirectToRoute('district_main', array('iddistrict'=>$districtFinderForms->getDistrictId()->getIddistrict()), 301);
        }
        $districtName = "";
        if($request->getSession()->has('iddistrict')){
            $districtName = $request->getSession()->get('district_name');
        }
        return $this->render('district/finddistrictform.html.twig',
                             array( 
                                 'form1' => $districtFinderForms->getView1(),
                                    'error'=>$districtFinderForms->getError(),
                                    'districtName' => $districtName,
                                    )                                   
                             );
    }
    
    
    /**
     * @Route("/national", name="national")
     */
    public function nationalAction(){
        $connection = $this->get('database_connection');
        
        $year = $connection->fetchAssoc("SELECT year FROM lwd_belongs_to_school ORDER BY year DESC");
        $sumquery = 'SELECT count(iddisability) FROM lwd 
            NATURAL JOIN lwd_has_disability NATURAL JOIN disability NATURAL JOIN lwd_belongs_to_school NATURAL JOIN school WHERE year = ?';

        $disabilities = $connection->fetchAll("SELECT disability_name, count(iddisability) as num_learners,($sumquery) as total 
            FROM lwd NATURAL JOIN lwd_has_disability NATURAL JOIN disability NATURAL JOIN lwd_belongs_to_school NATURAL JOIN school
            WHERE year = ? GROUP BY iddisability", array($year['year'], $year['year']));
        
        //schools in a zone
        $schoolsInMalawi = $connection->fetchAll('select emiscode, idzone from school');
        $dataConverter = $this->get('data_converter');
        $numOfSchools = count($schoolsInMalawi);//get the number of schools		
      
        return $this->render('national/national.html.twig',
                array('disabilities' => $disabilities,
                    'numOfSchools' => $numOfSchools,
                    'year' => $year['year'])
                );
    }
    public function removeTrailingSlashAction(Request $request){
        $pathInfo = $request->getPathInfo();
        $requestUri = $request->getRequestUri();
        $url = str_replace($pathInfo, rtrim($pathInfo, ' /'), $requestUri);
        return $this->redirect($url, 301);
    }
    /**
     * @Route("/add_user", name="add_user")
     */
    public function addUserAction(){
        $em = $this->getDoctrine()->getManager();
        $user = new User();
        $plainPassword = 'jonathanpass2';
        $encoder = $this->container->get('security.password_encoder');
        $encoded = $encoder->encodePassword($user, $plainPassword);
        $user->setPassword($encoded);
        $user->setUsername('jonathanadmin');
        $user->setEmail('jaymojew@gmail.com');
        $user->setRoles(array('ROLE_ADMIN'));
        $user->setEnabled(true);

        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute('homepage', array(), 301);
    }
    /**
     *@Route("/set_year/{year}", name="set_year", condition="request.isXmlHttpRequest()", options={"expose"= true})
     */
    public function setYearAction(Request $request, $year){
        $thisYear = date('Y');
        $result = 'invalid';
        if(preg_match('#\d{4}#', $year) == 1 && $year <= $thisYear){
            $request->getSession()->set('school_year', $year);
            $result = 'success';
        }
        return new \Symfony\Component\HttpFoundation\JsonResponse(array('result'=>$result));
    }
}
