<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Form\Type\SchoolFinderType;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        return $this->render('default/index.html.twig');
    }
    /**
     * @Route("/schoollist_ajax", name="school_ajax", condition="request.isXmlHttpRequest()",options={"expose"=true})
     */
    public function listSchoolsAction(Request $request){//this method will only be called through ajax
        $id = $request->query->get('id');
        $schools = $this->getDoctrine()->getRepository('AppBundle:School')->findByIddistrict($id);

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
     * @Route("/school", name="school")
     */
    public function schoolAction(Request $request){
        // $defaultData = array('field' => 'Please enter your name');/*passing this into the createForm() method causes the form values
        // to be put into an array as opposed to an object*/

        // /*create the array to be used for storing the list of schools for the dynamically populated select element. 
        // (set to an aempty array if not found in the session)  */
        // $session = $this->get('session');
        // $schoolList = array();
        // if($session->getFlashBag()->has('schoolList')){
        //     $schoolList = $session->getFlashBag()->get('schoolList');
        //     $em = $this->getDoctrine()->getManager();
        //     foreach($schoolList as $school){
        //         $em->persist($school);
        //     }
        // }
        // $form = $this->createForm(new SchoolFinderType($schoolList), $defaultData);/*create a form using the
        // SchoolFinderType form class*/
        
        // $form->handleRequest($request);

        // $emisCode;
        // if($form->isValid()){/*if the form was not submitted or contains invalid values, this returns
        // false*/
        //     $emisCode = $form->getData();           
        //     $school = $this->getDoctrine()->getRepository('AppBundle:School')->find($emisCode['school']);
        //     if($school){
        //         return $this->redirectToRoute('school_main',array('emisCode'=>$school->getId()), 301);
        //     }else{

        //     }             
        //  }

        return $this->render('school/school.html.twig');
    }
    /**
     * @Route("/findSchoolForm", name="find_school_form")
     */
    public function schoolSelectFormAction(){
        $schoolFinderForms = $this->container->get('school_finder');
        $formAction = $this->generateUrl('find_school_form');
        $schoolFinderForms->createForms($formAction);
        $schoolFinderForms->processForms();
        
        if($schoolFinderForms->areValid()){
            return $this->redirectToRoute('school_main',array('emisCode'=>$schoolFinderForms->getSchoolId()), 301);
        }

        return $this->render('school/findschoolform.html.twig',
                             array('form2' => $schoolFinderForms->getView2(),
                                    'form1' => $schoolFinderForms->getView1(),
                                    'error'=>$schoolFinderForms->getError())
                             );
    }
    /**
     * @Route("/zone", name="zone")
     */
    public function zoneAction(){
        return $this->render('zone/zone.html.twig');
    }
    /**
     * @Route("/district", name="district")
     */
    public function districtAction(){
        return $this->render('district/district.html.twig');
    }
    /**
     * @Route("/national", name="national")
     */
    public function nationalAction(){
        return $this->render('national/national.html.twig');
    }
}
