<?php
/*this is the controller for the school page
*it controls all links starting with school/
*/

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\Type\LearnerPersonalType;
use AppBundle\Form\Type\TeacherType;
use AppBundle\Entity\Guardian;
use AppBundle\Entity\Lwd;

class SchoolController extends Controller{
	/**
	 *@Route("/school/{emisCode}", name="school_main", requirements={"emisCode":"\d+"})
	 */
	public function schoolMainAction($emisCode, Request $request){

        $connection = $this->get('database_connection');
        $schools =  $connection->fetchAll('SELECT * FROM school NATURAL JOIN zone
        	NATURAL JOIN district WHERE emiscode = ?',array($emisCode));

        $sumquery = 'SELECT count(iddisability) FROM lwd 
        	NATURAL JOIN lwd_has_disability NATURAL JOIN disability NATURAL JOIN lwd_belongs_to_school
        	WHERE emiscode = ?';
        $disabilities = $connection->fetchAll("SELECT disability_name, count(iddisability) as num_learners,($sumquery) as total 
        	FROM lwd NATURAL JOIN lwd_has_disability NATURAL JOIN disability NATURAL JOIN lwd_belongs_to_school
        	WHERE emiscode = ? AND year = ? GROUP BY iddisability", array($emisCode,$emisCode,date('Y')));

        return $this->render('school/school2.html.twig',
                             array('school' => $schools[0],
                                    'disabilities' => $disabilities)
                             );
	}
	/**
	 *@Route("/school/{emisCode}/learners", name="school_learners", requirements={"emisCode":"\d+"}, options={"expose"= true})
	 */
	public function learnerAction($emisCode, Request $request){

		return $this->render('school/learners/learners_main.html.twig');
	}
	/**
	 *@Route("/findLearnerForm/{emisCode}", name="find_learner_form")
	 */
	public function findLearnerFormAction($emisCode, Request $request){//this controller will return the form used for selecting a learner
		$connection = $this->get('database_connection');
		$students = $connection->fetchAll('SELECT idlwd,first_name,last_name FROM lwd NATURAL JOIN lwd_belongs_to_school
			WHERE emiscode = ?', array($emisCode));

		//create the associative array to be used for the select list
		$choices = array();
		foreach ($students as $key => $row) {
			$choices[$row['idlwd']] = $row['first_name'].' '.$row['last_name'];
		}

		//create the form for choosing an existing student to edit
		$defaultData = array();
		$form = $this->createFormBuilder($defaultData, array(
					'action' => $this->generateUrl('find_learner_form', ['emisCode'=>$emisCode])))
						->add('learner','choice', array(
							'label' => 'Choose Learner',
							'placeholder'=>'Choose Learner',
							'choices'=> $choices,
							))
						->getForm();

		$form->handleRequest($request);

		if($form->isValid()){
			$formData = $form->getData();
			$learnerId = $formData['learner'];
			return $this->redirectToRoute('edit_learner_personal',array('emisCode'=>$emisCode,'learnerId'=>$learnerId));
		}
		return $this->render('school/learners/findlearnerform.html.twig', array(
											'form' => $form->createView()));
	}

        
         /**
    *@Route("/findTeacherForm/{emisCode}", name="find_teacher_form")
     */
    public function findTeacherFormAction($emisCode, Request $request){//this controller will return the form used for selecting a specialist teacher
    	$connection = $this->get('database_connection');
    	$teachers = $connection->fetchAll('SELECT idsnt,sfirst_name,slast_name FROM snt NATURAL JOIN school_has_snt
    			WHERE emiscode = ?', array($emisCode));

    $choices = array();
		foreach ($teachers as $key => $row) {
			$choices[$row['idsnt']] = $row['sfirst_name'].' '.$row['slast_name'];
		}

    	//create the form for choosing an existing teacher to edit      
        $defaultData = array();
		$form = $this->createFormBuilder($defaultData, array(
					'action' => $this->generateUrl('find_teacher_form', ['emisCode'=>$emisCode])))
						->add('teacher','choice', array(
							'label' => 'Choose Teacher',
							'placeholder'=>'Choose Teacher',
							'choices'=> $choices,
							))
						->getForm();

		$form->handleRequest($request);
                
                if($form->isValid()){
			$formData = $form->getData();
			$teacherId = $formData['teacher'];
			return $this->redirectToRoute('add_teacher',array('emisCode'=>$emisCode,'teacherId'=>$teacherId));
		}
              
	return $this->render('school/specialist_teacher/findteacherform.html.twig', array(
										'form' => $form->createView()));
    }
/**
     * @Route("/school/{emisCode}/learners/{learnerId}/1", name="edit_learner_personal", requirements ={"learnerId":"new|\d+"})
     */
    public function editLearnerPersonalAction(Request $request, $learnerId, $emisCode){
      	
      	$connection = $this->get('database_connection');
      	$defaultData = array();
      	if($learnerId != 'new'){/*if we are not adding a new learner, fill the form fields with
      		the data of the selected learner.*/
      		$learner = $connection->fetchAll('SELECT * FROM lwd, guardian
      			WHERE lwd.idguardian = guardian.idguardian AND idlwd = ?', array($learnerId));
      		$defaultData = $learner[0];
      		//convert the dates into their corresponding objects so that they will be rendered correctly by the form
      		$defaultData['dob'] = new \DateTime($defaultData['dob']);
      		$defaultData['gdob'] = new \DateTime($defaultData['gdob']);
      	}
      	$form1 = $this->createForm(new LearnerPersonalType(), $defaultData);

      	$form1->handleRequest($request);

      	if($form1->isValid()){
      		$formData = $form1->getData();
      		$id_lwd = $formData['idlwd'];
      		$id_guardian = $formData['idguardian'];
      		$guardian; 
      		$learner;

      		//check if this record is being edited or created anew
      		if($learnerId == 'new'){
      			$guardian = new Guardian();
      			$learner = new Lwd();
      			$learner->setIdlwd($formData['idlwd']);
      		}else{//if it is being edited, then update the records that already exist 
      			$guardian = $this->getDoctrine()->getRepository('AppBundle:Guardian')->findOneByIdguardian($id_guardian);
      			$learner = $this->getDoctrine()->getRepository('AppBundle:Lwd')->findOneByIdlwd($id_lwd);
      		}
			//set the fields for guardian
      		$guardian->setGfirstName($formData['gfirst_name']);
      		$guardian->setGlastName($formData['glast_name']);
      		$guardian->setGsex($formData['gsex']);
      		$guardian->setGaddress($formData['gaddress']);
      		$guardian->setGdob($formData['gdob']);
      		$guardian->setOccupation($formData['occupation']);
      		$guardian->setIncomeLevel($formData['income_level']);
      		$guardian->setDistrict($formData['district']);  
      		//set the fields for learner
      		$learner->setFirstName($formData['first_name']);
      		$learner->setLastName($formData['last_name']);
      		$learner->setSex($formData['sex']);
      		$learner->setInitials($formData['initials']);
      		$learner->setHomeaddress($formData['home_address']);
      		$learner->setFirstName($formData['first_name']);
      		$learner->setDob($formData['dob']);
      		$learner->setIdguardian($guardian);
      		$learner->setGuardianRelationship($formData['guardian_relationship']);

      		//write the objects to the database
      		$em = $this->getDoctrine()->getManager();
      		$em->persist($guardian);
      		$em->persist($learner);
      		$em->flush();

      		//if this is a new learner, add an entry in the lwd_belongs_to_school table
      		if($learnerId == 'new'){
      			$values = ['idlwd'=>$id_lwd, 'emiscode'=>$emisCode, 'year'=> new \DateTime('y')];
      			$types = [\PDO::PARAM_INT, \PDO::PARAM_INT, 'datetime'];
      			$connection->insert('lwd_belongs_to_school',$values, $types);

      			return $this->redirectToRoute('edit_learner_disability',['emisCode'=>$emisCode, 'learnerId'=>$id_lwd], 301);
      		}
      		
      	}

      	//if this is a new learner being added, we want to make the id field uneditable
      	if($learnerId != 'new'){
      		$readOnly = true;
      	}else{
      		$readOnly = false;
      	}
      	
        return $this->render('school/learners/edit_learner_personal.html.twig', array(
        	'form1'=>$form1->createView(),
        	'readonly' => $readOnly));
    }
    /**
     * @Route("/school/{emisCode}/learners/{learnerId}/2", name="edit_learner_disability", requirements ={"learnerId":"new|\d+"})
     */
    public function editLearnerDisabilityAction(Request $request, $learnerId){
    	return $this->render('school/learners/edit_learner_disability.html.twig');
    }
       
    /**
     * @Route("/school/{emisCode}/teachers/{teacherId}/edit", name="add_teacher", requirements ={"teacherId":"new|\d+"})
     */
    public function addTeacherAction(Request $request, $teacherId, $emisCode){//this method will only be called through ajax
      	$connection = $this->get('database_connection');
      	$defaultData = array();
      	if($teacherId != 'new'){/*if we are not adding a new learner, fill the form fields with
      		the data of the selected learner.*/
      		$teacher = $connection->fetchAll('SELECT * FROM snt
      			WHERE idsnt = ?', array($teacherId));
      		$defaultData = $teacher[0];
      		//convert the dates into their corresponding objects so that they will be rendered correctly by the form
      		$defaultData['year_started'] = new \DateTime($defaultData['year_started']);
      		//$defaultData['gdob'] = new \DateTime($defaultData['gdob']);
      	}
      
      	$form2 = $this->createForm(new TeacherType(), $defaultData);
        
        $form2->handleRequest($request);

      	if($form2->isValid()){
      		$formData = $form2->getData();
      		$id_snt = $formData['idsnt'];
      		$teacher;

      		//check if this record is being edited or created anew
      		if($teacherId == 'new'){
      			$teacher = new Snt();
      			$teacher->setIdsnt($formData['idsnt']);
      		}
                //else{//if it is being edited, then update the records that already exist 
      		//	$guardian = $this->getDoctrine()->getRepository('AppBundle:Guardian')->findOneByIdguardian($id_guardian);
      		//	$learner = $this->getDoctrine()->getRepository('AppBundle:Lwd')->findOneByIdlwd($id_lwd);
      		//}
			 
      		//set the fields for teacher
      		$teacher->setSFirstName($formData['sfirst_name']);
      		$teacher->setSLastName($formData['slast_name']);
      		$teacher->setSSex($formData['sSex']);
      		$teacher->setSinitials($formData['sinitials']);
      		$teacher->setQualification($formData['qualification']);
                $teacher->setSpeciality($formData['speciality']);
                $teacher->setYearStarted($formData['yearStarted']);
      		
      		//write the objects to the database
      		$em = $this->getDoctrine()->getManager();
      		
      		$em->persist($teacher);//tell the entity manager to keep track of this entity
      		$em->flush();//write all entities that are being tracked to the database

      		//Start from here
                //if this is a new learner, add an entry in the lwd_belongs_to_school table
      		if($learnerId == 'new'){
      			$values = ['idsnt'=>$id_snt, 'emiscode'=>$emisCode, 'year'=> new \DateTime('y')];
      			$types = [\PDO::PARAM_INT, \PDO::PARAM_INT, 'datetime'];
      			$connection->insert('lwd_belongs_to_school',$values, $types);

      			return $this->redirectToRoute('edit_learner_disability',['emisCode'=>$emisCode, 'learnerId'=>$id_lwd], 301);
      		}
      		
      	}

      	//if this is a new learner being added, we want to make the id field uneditable
      	if($learnerId != 'new'){
      		$readOnly = true;
      	}else{
      		$readOnly = false;
      	}
      	
        return $this->render('school/learners/edit_learner_personal.html.twig', array(
        	'form1'=>$form1->createView(),
        	'readonly' => $readOnly));
        

    /**
    *@Route("/findTeacherForm/{emisCode}", name="find_teacher_form")
     */
    public function findTeacherFormAction($emisCode){//this controller will return the form used for selecting a specialist teacher
    	$connection = $this->get('database_connection');
    	$teachers = $connection->fetchAll('SELECT idsnt,sfirst_name,slast_name FROM snt NATURAL JOIN school_has_snt
    			WHERE emiscode = ?', array($emisCode));

    $choices = array();
		foreach ($teachers as $key => $row) {
			$choices[$row['idsnt']] = $row['sfirst_name'].' '.$row['slast_name'];
		}

    	//create the form for choosing an existing student to edit
	$defaultData = array();
	$form = $this->createFormBuilder($defaultData)
					->add('teacher','choice', array(
						'label' => 'Choose Teacher',
						'placeholder'=>'Choose Teacher',
						'choices'=> $choices,
						))
					->getForm();
	return $this->render('school/specialist_teacher/findteacherform.html.twig', array(
										'form' => $form->createView()));
    }
     
    /**
     * @Route("/school/{emisCode}/teachers/edit", name="add_teacher",options={"expose"=true})
     */
    public function addTeacherAction(Request $request){//this method will only be called through ajax
      	
      	$defaultData = array();
      	$form2 = $this->createForm(new TeacherType(), $defaultData);

        return $this->render('school/specialist_teacher/add_teacher.html.twig', array('form2'=>$form2->createView()));
    }
    /**
	 *@Route("/school/{emisCode}/teachers", name="school_teachers", requirements={"emisCode":"\d+"}, options={"expose"= true})
	 */
	public function teacherAction($emisCode, Request $request){

		return $this->render('school/specialist_teacher/teachers_main.html.twig');
	}

}
	
 ?>