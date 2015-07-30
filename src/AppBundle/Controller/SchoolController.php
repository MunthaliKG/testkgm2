<?php
/*this is the controller for the school page
*it controls all links starting with school/
*/

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\Type\LearnerPersonalType;
use AppBundle\Form\Type\RoomStateType;
use AppBundle\Form\Type\TeacherType;
use AppBundle\Entity\Guardian;
use AppBundle\Entity\Lwd;
use AppBundle\Entity\RoomState;
use AppBundle\Entity\Snt;
use AppBundle\Entity\SchoolHasSnt;

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
	 *@Route("/school/{emisCode}/materials", name="school_materials", requirements={"emisCode":"\d+"}, options={"expose"= true})
	 */
        public function materialsAction($emisCode, Request $request){

		return $this->render('school/materials/materials_main.html.twig');
	}
        /**
	 *@Route("/findMaterialForm/{emisCode}", name="find_school_materials")
	 */
        public function findMaterialFormAction($emisCode, Request $request){//this controller will return the form used for selecting a learner
		$connection = $this->get('database_connection');
		$materials = $connection->fetchAll('SELECT room_id, year FROM room_state WHERE emiscode = ?', array($emisCode));
                   //room_id, year, enough_light, enough_space, adaptive_chairs, accessible, enough_ventilation, other_observations
		//create the associative array to be used for the select list
		$choices = array();
		foreach ($materials as $key => $row) {
			$choices[$row['room_id']] = $row['room_id'].': '.$row['year'];
		}

		//create the form for choosing an existing student to edit
		$defaultData = array();
		$form = $this->createFormBuilder($defaultData, array(
					'action' => $this->generateUrl('find_school_materials', ['emisCode'=>$emisCode])))
						->add('material','choice', array(
							'label' => 'Choose Material',
							'placeholder'=>'Choose Material',
							'choices'=> $choices,
							))
						->getForm();

		$form->handleRequest($request);

		if($form->isValid()){
			$formData = $form->getData();
			$materialId = $formData['material'];
			return $this->redirectToRoute('edit_school_material',array('emisCode'=>$emisCode,'materialId'=>$materialId));
		}
		return $this->render('school/materials/findmaterialform.html.twig', array(
											'form' => $form->createView()));
	}
        
        /**
        * @Route("/school/{emisCode}/materials/{materialId}", name="edit_school_material", requirements={"materialId":"new|\S"})
        */
        public function editMaterialAction(Request $request, $materialId, $emisCode){
            $connection = $this->get('database_connection');
            $defaultData = array();
            if($materialId != 'new'){/*if we are not adding a new material, fill the form fields with
      		the data of the selected learner.*/
      		$materials = $connection->fetchAll('SELECT * FROM room_state
      			WHERE room_id = ? AND emiscode = ?', array($materialId, $emisCode));
      		$defaultData = $materials[0];
      		//convert the dates into their corresponding objects so that they will be rendered correctly by the form
      		$defaultData['year'] = new \DateTime($defaultData['year']);
      		//$defaultData['gdob'] = new \DateTime($defaultData['gdob']);
            }
            
            $form1 = $this->createForm(new RoomStateType(), $defaultData);
                 
            $form1->handleRequest($request);
            
            
            if($form1->isValid()){
      		//echo 'good';
                $formData = $form1->getData();
      		//echo $formData['year'];
                //exit;
                $id_room = $formData['idRoom'];
                $material;
                
                
      		//check if this record is being edited or created anew
      		if($materialId == 'new'){
      			$material = new RoomState();
      			$material->setIdRoom($formData['idRoom']);
      		}else{//if it is being edited, then update the records that already exist 
      			$material = $this->getDoctrine()->getRepository('AppBundle:RoomState')->findOneByIdRoom($id_room);
      			
      		}
			//set the fields for material
      		$material->setAccessible($formData['accessible']);
      		$material->setAdaptiveChairs($formData['adaptiveChairs']);
                $material->setEmiscode($this->getDoctrine()->getRepository('AppBundle:School')->findOneByEmiscode($emisCode));
                $material->setEnoughLight($formData['enoughLight']);
                $material->setEnoughVentilation($formData['enoughVentilation']);
                $material->setOtherObservations($formData['otherObservations']);
                $material->setIdRoom($formData['idRoom']);
                $material->setYear($formData['year']);

               
                //reset entity manager
                //$container->set('doctrine.orm.entity_manager', null);
                //$container->set('doctrine.orm.default_entity_manager', null);
      		//write the objects to the database
      		$em = $this->getDoctrine()->getManager();
                //echo $material->getEmiscode();
                //exit;
      		$em->persist($material);
      		$em->flush();

      		
                
            }
            //if this is a new learner being added, we want to make the id field uneditable
            if($materialId != 'new'){
                $readOnly = true;
            }else{
                $readOnly = false;
            }
      	
            return $this->render('school/materials/edit_school_material.html.twig', array(
                    'form1'=>$form1->createView(),
                    'readonly' => $readOnly));
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
    *@Route("/findTeacherForm/{emisCode}/", name="find_teacher_form", requirements={"teacherId":"new|\d+"})
     */
    public function findTeacherFormAction(Request $request, $emisCode){//this controller will return the form used for selecting a specialist teacher
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
     * @Route("/school/{emisCode}/teachers/{teacherId}/edit", name="add_teacher", requirements ={"teacherId":"new|\d+"})
     */
    public function addTeacherAction(Request $request, $teacherId, $emisCode){//this method will only be called through ajax
      	
        $connection = $this->get('database_connection');
      	$defaultData = array();
        
        if($teacherId != 'new'){/*if we are not adding a new learner, fill the form fields with
      		the data of the selected learner.*/
            $teacher = $connection->fetchAll('SELECT * FROM snt NATURAL JOIN school_has_snt Where idsnt = ?', array($teacherId));
            $defaultData = $teacher[0];
                //SELECT idsnt, sfirst_name, slast_name, sinitials, s_sex, qualification, speciality, year_started, year FROM `snt` WHERE 1
      		//convert the dates into their corresponding objects so that they will be rendered correctly by the form
      		$defaultData['year_started'] = new \DateTime($defaultData['year_started'].'-1-1');/*append -1-1 at the end to make sure the string is correclty converted to 
      		a DateTime object*/
            $defaultData['year'] = new \DateTime($defaultData['year'].'-1-1');
      		$defaultData['speciality'] = explode(',',$defaultData['speciality']);/*convert the SET value of MySQL to corresponding array in Php
      		to enable correct rendering of choices in the form*/
        }
        //['idsnt'=>$defaultData['idsnt'], 'sfirst_name'=>$defaultData['sfirst_name'], 'slast_name'=>$defaultData['slast_name'], 'sinitials'=>$defaultData['sinitials'], 's_sex'=>$defaultData['s_sex'], 'qualification'=>$defaultData['qualification'], 'speciality'=>$defaultData['speciality'], 'year_started'=>$defaultData['year_started'], 'year'=>$defaultData['year']]
        //$form2 = $this->createForm(new TeacherType(), $teacher[0]);
      	//$form2 = $this->createForm(new TeacherType(), $defaultData);
        //$form2=  $this->createForm(new TeacherType(), $defaultData);
        $form2=  $this->createForm(new TeacherType(), $defaultData);
        $form2->handleRequest($request);
        
      	if($form2->isValid()){
            $formData = $form2->getData();
            $teacher;

            //check if this record is being edited or created anew
            if($teacherId == 'new'){
                $teacher = new Snt();
            }
            else
            {//if it is being edited, then update the records that already exist 
            	$teacher = $this->getDoctrine()->getRepository('AppBundle:Snt')->findOneByIdsnt($teacherId);
            }

            //set the fields for teacher
            $teacher->setSFirstName($formData['sfirst_name']);             
            $teacher->setSLastName($formData['slast_name']);
            $teacher->setSSex($formData['s_sex']);
            $teacher->setSinitials($formData['sinitials']);
            $teacher->setQualification($formData['qualification']);
            // foreach($formData['speciality'] as $key=>$value){
            // 	echo $key.': '.$value.'<br>';
            // };
            // exit;
            $teacher->setSpeciality($formData['speciality']);
            $teacher->setYearStarted($formData['year_started']->format('Y-m-d'));

            //write the objects to the database
            $em = $this->getDoctrine()->getManager();

            $em->persist($teacher);//tell the entity manager to keep track of this entity
            $em->flush();//write all entities that are being tracked to the database

            //Start from here
            //if this is a new teacher, add an entry in the school_has_snt table
            if($teacherId == 'new'){
            	$id_snt = $teacher->getIdsnt();
                $values = ['emiscode'=>$emisCode, 'idsnt'=>$id_snt, 'year'=> new \DateTime('y')];
                $types = [\PDO::PARAM_INT, \PDO::PARAM_INT, 'datetime'];
                $connection->insert('school_has_snt',$values, $types);

                return $this->redirectToRoute('add_teacher',['emisCode'=>$emisCode, 'teacherId'=>$id_snt], 301);
            }      		
      	}

      	//if this is not a new teacher being added, we want to make the id field uneditable
      	if($teacherId != 'new'){
      		$readOnly = true;
      	}else{
      		$readOnly = false;
      	}
      	
        return $this->render('school/specialist_teacher/add_teacher.html.twig', array(
        	'form2'=>$form2->createView(),
        	'readonly'=> $readOnly));        
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

      		$metadata = $em->getClassMetaData(get_class($learner));
			$metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
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
	 *@Route("/school/{emisCode}/teachers", name="school_teachers", requirements={"emisCode":"\d+"}, options={"expose"= true})
	 */
	public function teacherAction($emisCode, Request $request){

		return $this->render('school/specialist_teacher/teachers_main.html.twig');
	}

}
	
 ?>