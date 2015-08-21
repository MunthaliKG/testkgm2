<?php
/*this is the controller for the school page
*it controls all links starting with school/
*/

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\ChoiceList;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Form\Type\LearnerPersonalType;
use AppBundle\Entity\Lwd;
use AppBundle\Form\Type\RoomStateType;
use AppBundle\Entity\RoomState;
use AppBundle\Form\Type\TeacherType;
use AppBundle\Entity\Snt;
use AppBundle\Form\Type\LearnerDisabilityType;
use AppBundle\Form\Type\LearnerPerformanceType;
use AppBundle\Entity\Performance;
use AppBundle\Form\Type\NeedsType;
use AppBundle\Entity\Guardian;
use AppBundle\Entity\SchoolHasSnt;
use AppBundle\Entity\Need;
use AppBundle\Entity\ResourceRoom;
use AppBundle\Form\Type\ResourceRoomType;
use AppBundle\Entity\LwdHasDisability;

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

		$session = $request->getSession();
		//keep the emiscode of the selected school in the session so we can always redirect to it until the next school is chosen
		$session->set('emiscode', $emisCode);
		//keep the name of the selected school in the session to access it from the school selection form
		$session->set('school_name', $schools[0]['school_name']);

		return $this->render('school/school2.html.twig',
			array('school' => $schools[0],
				'disabilities' => $disabilities)
			);
	}
        /**
	 *@Route("/school/{emisCode}/materials/{link}", name="school_materials", requirements = {"emisCode":"\d+", "link":"fresh|resource|room"}, options={"expose"= true})
	 */
        public function materialsAction($emisCode, $link, Request $request){

            if($link == 'resource'){
                return $this->render('school/materials/resources_main.html.twig');
            }else {
                return $this->render('school/materials/materials_main.html.twig');
            }
        }
         /**
	 *@Route("/findResourceForm/{emisCode}", name="find_need_materials")
	 */
        public function findResourceFormAction($emisCode, Request $request){//this controller will return the form used for selecting a learner
	$connection = $this->get('database_connection');
		$needs = $connection->fetchAll('SELECT * FROM need n NATURAL JOIN school_has_need s WHERE s.emiscode = ?', array($emisCode));
                   
		$choices = array();
		foreach ($needs as $key => $row) {
			$choices[$row['idneed']] = $row['idneed'].': '.$row['needname'];
		}

		//create the form for choosing an existing student to edit
        	$defaultData = array();
        	$form = $this->createFormBuilder($defaultData, array(
        		'action' => $this->generateUrl('find_need_materials', ['emisCode'=>$emisCode])))
        	->add('need','choice', array(
        		'label' => 'Choose Resource',
        		'placeholder'=>'Edit Resource',
        		'choices'=> $choices,
        		))
        	->getForm();

        	$form->handleRequest($request);

        	if($form->isValid()){
        		$formData = $form->getData();
        		$needId = $formData['need'];
        		return $this->redirectToRoute('edit_resource_material',array('emisCode'=>$emisCode,'needId'=>$needId));
        	}
        	return $this->render('school/materials/findresourceform.html.twig', array(
        		'form' => $form->createView()));
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
        		'label' => 'Choose Room',
        		'placeholder'=>'Edit Room',
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
        * @Route("/school/{emisCode}/needs/{needId}", name="edit_resource_material", requirements={"needId":"new|\d+"})
        */
        public function editResourceAction(Request $request, $needId, $emisCode){
            $connection = $this->get('database_connection');
            $defaultData = array();
            if($needId != 'new'){/*if we are not adding a new material, fill the form fields with
            	the data of the selected learner.*/
            	$needs = $connection->fetchAll('SELECT * FROM school_has_need
            		WHERE idneed = ? AND emiscode = ?', array($needId, $emisCode));
            	$defaultData = $needs[0];
      		//convert the dates into their corresponding objects so that they will be rendered correctly by the form
      		//$defaultData['year'] = new \DateTime($defaultData['year'].'-1-1');
      		$defaultData['year_recorded'] = new \DateTime($defaultData['year_recorded'].'-1-1');
                $defaultData['date_procured'] = new \DateTime($defaultData['date_procured']);
                $defaultData['idneed_2'] = $needs[0]['idneed'];
            }
            //generate an array to pass into form for a select list options    
            $needs2 = $connection->fetchAll('SELECT idneed, needname FROM need');
                   
            $choices = array();
            foreach ($needs2 as $key => $row) {
                    $choices[$row['idneed']] = $row['idneed'].': '.$row['needname'];
            }
            $form1 = $this->createForm(new ResourceRoomType($choices), $defaultData);
            
            $form1->handleRequest($request);
                        
            if($form1->isValid()){
            	$formData = $form1->getData();
                $id_need = $formData['idneed'];
                $need;
                $update = '';
                //$update = '';
      		//check if this record is being edited or created anew
      		if($needId == 'new'){
                    $need = new \AppBundle\Entity\ResourceRoom();
                    //$material->setIdRoom($formData['idRoom']);   	
      		}else{//if it is being edited, then update the records that already exist 
                    $need = $this->getDoctrine()->getRepository('AppBundle:ResourceRoom')->findOneByIdneed($formData['idneed_2']);
                    
                    //check if any of the fields has been changed
                    //and append the change in updates field with a date stamp
                    if ($defaultData['updates'] != null){
                        $update = $defaultData['updates'];}
                    $date = date('Y-m-d');
                    if($defaultData['quantity'] != $formData['quantity']){
                        $update = $update. 'Qty: '.$defaultData['quantity']. 
                            ' --> '. $formData['quantity']. ' on '. $date. ';';           	
                    }
                    if($defaultData['state'] != $formData['state']){
                        $update = $update. 'State: '.$defaultData['state']. 
                            ' --> '. $formData['state']. ' on '. $date. ';';           	
                    }
                    if($defaultData['available_in_rc'] != $formData['available_in_rc']){
                        $update = $update. 'AvRC: '.$defaultData['available_in_rc']. 
                            ' --> '. $formData['available_in_rc']. ' on '. $date. ';';           	
                    }
                    if($defaultData['date_procured'] != $formData['date_procured']){
                        $update = $update. 'DateP: '.$defaultData['date_procured']->format('Y-m-d'). 
                            ' --> '. $formData['date_procured']->format('Y-m-d'). ' on '. $date. ';';           	
                    }
                    if($defaultData['year_recorded'] != $formData['year_recorded']){
                        $update = $update. 'YearR: '.$defaultData['year_recorded']->format('Y'). 
                            ' --> '. $formData['year_recorded']->format('Y'). ' on '. $date. ';';           	
                    }
                    
                    //maintain the need id regardless of changes
                    //think of a better way to do this later
                    /*if($defaultData['idneed'] != $formData['idneed']){
                       $formData['idneed'] = $defaultData['idneed'];           	
                    }*/
   		}

                if ($update != null){$need->setUpdates($update);}               
                $need->setEmiscode($this->getDoctrine()->getRepository('AppBundle:School')->findOneByEmiscode($emisCode));
                
                //If idneed is disabled do the right thing
                if ($needId == 'new'){
                    $need->setIdneed($this->getDoctrine()->getRepository('AppBundle:Need')->findOneByIdneed($formData['idneed']));
                }else{
                    $need->setIdneed($this->getDoctrine()->getRepository('AppBundle:Need')->findOneByIdneed($formData['idneed_2']));
                }
                $need->setDateProcured($formData['date_procured']);
                $need->setYearRecorded($formData['year_recorded']->format('Y'));
                $need->setState($formData['state']);
                $need->setAvailableInRc($formData['available_in_rc']);
                $need->setQuantity($formData['quantity']);
                
                $em = $this->getDoctrine()->getManager();
                $em->persist($need);
      		$em->flush();
               
                //reproduce new entered details for validation
                if($needId == 'new'){
                    return $this->redirectToRoute('edit_resource_material',['emisCode'=>$emisCode, 'needId'=>$id_need], 301);
                }
            }
            //if this is a not new need being added, we want to make the id field uneditable
      	if($needId != 'new'){
            $readonly = true;
            $disabled = true;
            $required = false;
            //set the value to the session needId if the field is null
            $empty_data = $needId;
      	}else{
            $readonly = false;
            $disabled = false;
            $required = true;
            $empty_data = '';
      	}
        
        
        
      	return $this->render('school/materials/edit_resource_material.html.twig', array(
      		'form1'=>$form1->createView(),
      		'disabled' => $disabled));
                //'disabled' => $disabled,'empty_data' => $empty_data));
      }
        /**
        * @Route("/school/{emisCode}/materials/{materialId}", name="edit_school_material", requirements={"materialId":"new|\S+"})
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
      		//$defaultData['year'] = new \DateTime($defaultData['year'].'-1-1');
      		$defaultData['year'] = new \DateTime($defaultData['year'].'-1-1');
            }
       
            $form1 = $this->createForm(new RoomStateType(), $defaultData);
            
            $form1->handleRequest($request);
                        
            if($form1->isValid()){
            	$formData = $form1->getData();
                $id_room = $formData['room_id'];
                $material;
                $update = '';
      		//check if this record is being edited or created anew
      		if($materialId == 'new'){
                    $material = new RoomState();
                    //$material->setIdRoom($formData['idRoom']);   	
      		}else{//if it is being edited, then update the records that already exist 
                    $material = $this->getDoctrine()->getRepository('AppBundle:RoomState')->findOneByIdRoom($id_room);	
                    
                    //check if any of the fields has been changed
                    //and append the change in updates field with a date stamp
                    $update = $defaultData['updates'];
                    $date = date('Y-m-d');
                    if($defaultData['enough_light'] != $formData['enough_light']){
                        $update = $update. 'EL: '.$defaultData['enough_light']. 
                            ' --> '. $formData['enough_light']. ' on '. $date. ';';           	
                    }
                    if($defaultData['enough_space'] != $formData['enough_space']){
                        $update = $update. 'ES: '.$defaultData['enough_space']. 
                            ' --> '. $formData['enough_space']. ' on '. $date. ';';           	
                    }
                    if($defaultData['enough_ventilation'] != $formData['enough_ventilation']){
                        $update = $update. 'EV: '.$defaultData['enough_ventilation']. 
                            ' --> '. $formData['enough_ventilation']. ' on '. $date. ';';           	
                    }
                    if($defaultData['adaptive_chairs'] != $formData['adaptive_chairs']){
                        $update = $update. 'AdC: '.$defaultData['adaptive_chairs']. 
                            ' --> '. $formData['adaptive_chairs']. ' on '. $date. ';';           	
                    }
                    if($defaultData['access'] != $formData['access']){
                        $update = $update. 'Acc: '.$defaultData['access']. 
                            ' --> '. $formData['access']. ' on '. $date. ';';           	
                    }
                    if($defaultData['room_type'] != $formData['room_type']){
                        $update = $update. 'RType: '.$defaultData['room_type']. 
                            ' --> '. $formData['room_type']. ' on '. $date. ';';           	
                    }
      		}
 
                $material->setUpdates($update);
		//set the fields for material
                $material->setIdRoom($formData['room_id']);
                $material->setEmiscode($this->getDoctrine()->getRepository('AppBundle:School')->findOneByEmiscode($emisCode));
                $material->setYearStarted($formData['year']->format('Y-m-d'));
                $material->setEnoughLight($formData['enough_light']);
      		$material->setEnoughSpace($formData['enough_space']);  
      		$material->setAdaptiveChairs($formData['adaptive_chairs']);
                $material->setAccess($formData['access']);
                $material->setEnoughVentilation($formData['enough_ventilation']);
                $material->setOtherObservations($formData['other_observations']);
                $material->setRoomType($formData['room_type']);

                $em = $this->getDoctrine()->getManager();
                $em->persist($material);
      		$em->flush();
               
                //reproduce new entered details for validation
                if($materialId == 'new'){
                    return $this->redirectToRoute('edit_school_material',['emisCode'=>$emisCode, 'materialId'=>$id_room], 301);
                }
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
		$defaultData = array('learner' => $request->get('learnerId'));
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
    *@Route("/findTeacherForm/{emisCode}/", name="find_teacher_form", requirements={"teacherId":"new|\S+"})
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
     * @Route("/school/{emisCode}/teachers/{teacherId}/edit", name="add_teacher", requirements ={"teacherId":"new|\S+"})
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
            
            $defaultData['s_dob'] = new \DateTime($defaultData['s_dob']);
            
            $defaultData['year_started'] = new \DateTime($defaultData['year_started'].'-1-1');/*append -1-1 at the end to make sure the string is correclty converted to 
      		a DateTime object*/

            $defaultData['year'] = new \DateTime($defaultData['year'].'-1-1');
            $defaultData['speciality'] = explode(',',$defaultData['speciality']);/*convert the SET value of MySQL to corresponding array in Php
      		to enable correct rendering of choices in the form*/
      	}
        
      	$form2=  $this->createForm(new TeacherType(), $defaultData);
      	$form2->handleRequest($request);

      	if($form2->isValid()){
            $formData = $form2->getData();
            $teacher;
            $schoolHasSnt;

            //check if this record is being edited or created anew
            if($teacherId == 'new'){
                    $teacher = new Snt();
                    $schoolHasSnt = new SchoolHasSnt();
            }else{
                //if it is being edited, then update the records that already exist 
            	$teacher = $this->getDoctrine()->getRepository('AppBundle:Snt')->findOneByIdsnt($teacherId);
                $schoolHasSnt = $this->getDoctrine()->getRepository('AppBundle:SchoolHasSnt')->findOneBy(array('idsnt'=>$teacherId, 'emiscode'=>$emisCode, 'year'=>$defaultData['year']->format('Y')));
            }

            //set the fields for teacher
            $teacher->setEmploymentNumber($formData['employment_number']);
            $teacher->setSFirstName($formData['sfirst_name']);             
            $teacher->setSLastName($formData['slast_name']);
            $teacher->setSdob($formData['s_dob']);
            $teacher->setSSex($formData['s_sex']);
            $teacher->setSinitials($formData['sinitials']);
            $teacher->setQualification($formData['qualification']);
            $teacher->setSpeciality($formData['speciality']);
            $teacher->setYearStarted($formData['year_started']->format('Y'));
            
            
//echo 'fine';
//exit;
            //write the objects to the database
            $em = $this->getDoctrine()->getManager();

            //tell the entity manager to keep track of this entity
            $em->persist($teacher);      
            $em->flush();//write all entities that are being tracked to the database

            $schoolHasSnt->setSntType($formData['snt_type']);
            $schoolHasSnt->setEmiscode($this->getDoctrine()->getRepository('AppBundle:School')->findOneByEmiscode($emisCode));
            $schoolHasSnt->setIdsnt($this->getDoctrine()->getRepository('AppBundle:Snt')->findOneByIdsnt($teacher->getIdsnt()));
            $schoolHasSnt->setYear($formData['year']->format('Y'));
            
            //tell the entity manager to keep track of this entity
            $em->persist($schoolHasSnt);
            $em->flush();
            //Start from here
            //if this is a new teacher, add an entry in the school_has_snt table
            /*if($teacherId == 'new'){
            	$id_snt = $teacher->getIdsnt();
            	$values = ['emiscode'=>$emisCode, 'idsnt'=>$id_snt, 'year'=> new \DateTime('y'), 'snt_type'=>$formData['snt_type']];
            	$types = [\PDO::PARAM_INT, \PDO::PARAM_INT, 'datetime', 'text'];
            	$connection->insert('school_has_snt',$values, $types);
                $connection->update('school_has_snt');

            	return $this->redirectToRoute('add_teacher',['emisCode'=>$emisCode, 'teacherId'=>$id_snt], 301);
            }  */ 
            return $this->redirectToRoute('add_teacher',['emisCode'=>$emisCode, 'teacherId'=>$teacher->getIdsnt()], 301);
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
     * @Route("/school/{emisCode}/learners/{learnerId}/personal", name="edit_learner_personal", requirements ={"learnerId":"new|\d+"})
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
      		$learner->setDistanceToSchool($formData['distance_to_school']);
      		$learner->setIdguardian($guardian);
      		$learner->setGuardianRelationship($formData['guardian_relationship']);

      		//write the objects to the database
      		$em = $this->getDoctrine()->getManager();
      		$em->persist($guardian);
      		$em->persist($learner);

      		//force the entity to use the provided learner id as opposed to an auto-generated one
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
     * @Route("/school/{emisCode}/learners/{learnerId}/need", name="edit_learner_disability", requirements ={"learnerId":"new|\d+"})
     */
    public function editLearnerDisabilityAction(Request $request, $learnerId, $emisCode){

    	$forms = array(); //array to keep the forms: there could be more than one disability form for a learner
    	$needForms = array(); //array to keep forms for the needs of each disability
    	$connection = $this->get('database_connection'); 
    	$disabilities = $connection->fetchAll("SELECT * FROM disability");

    	if($learnerId != 'new'){
    		$learnerDisabilities = $connection->fetchAll("SELECT * FROM lwd_has_disability WHERE idlwd = ?", array(
    			$learnerId));
    		if($learnerDisabilities){
    			$formCounter = 1;
    			//prepare SQL statements to be executed with each iteration
    			$levelsStmt = $connection->prepare("SELECT idlevel, level_name FROM disability_has_level NATURAL JOIN level 
    					WHERE iddisability = ?");
    			$needsStmt = $connection->prepare("SELECT idneed, needname FROM disability_has_need NATURAL JOIN need 
    					WHERE iddisability = ?");
    			$needsRowsStmt = $connection->prepare("SELECT idneed FROM lwd_has_disability_has_need WHERE idlwd = ? 
    					AND iddisability = ?");

    			//iterate over each disability for this learner
    			foreach($learnerDisabilities as $key => $disability){
    				//get the levels to show in the form for this disability
    				$levelsStmt->bindParam(1, $disability['iddisability']);
    				$levelsStmt->execute();
    				$levels = $levelsStmt->fetchAll();

    				$disability['identification_date'] = new \DateTime($disability['identification_date']);
    				$disability['iddisability_2'] = $disability['iddisability'];//set default data for the hidden field since the true iddisability will be disabled
    				$forms[] = $this->createForm(new LearnerDisabilityType($disabilities, $levels, $formCounter), $disability); 
    				//get the needs for this disability
    				$needsStmt->bindParam(1, $disability['iddisability']);
    				$needsStmt->execute();
    				$needs = $needsStmt->fetchAll();
    				//get the needs that the learner has access to for this disability
    				$needsRowsStmt->bindParam(1, $learnerId);
    				$needsRowsStmt->bindParam(2, $disability['iddisability']);
    				$needsRowsStmt->execute();
    				$availableNeedsRows = $needsRowsStmt->fetchAll();
    				//get the ids of all the available needs as a single array
    				$availableNeeds = array_column($availableNeedsRows, 'idneed');
    				$needForms[] = $this->createForm(new NeedsType($needs, $formCounter), ['needs'=>$availableNeeds, 'iddisability'=>$disability['iddisability']]);
    				$formCounter++;
    			}
    			$levelsStmt->closeCursor();
    			$needsStmt->closeCursor();
    			$needsRowsStmt->closeCursor();
    		}
    		//process each of the forms
    		$formCounter = 1;
    		foreach($forms as $form){
    			$form->handleRequest($request);
    			if($form->isValid()){
    				$formData = $form->getData();
    				$em = $this->getDoctrine()->getManager();
    				$lwdHasDisability = $em->getRepository('AppBundle:LwdHasDisability')->findOneBy([
    					'idlwd'=>$learnerId,
    					'iddisability' =>$formData['iddisability_2']
    					]
					);
					if($form->get('remove')->isClicked()){//if the remove button was clicked for this record
						$em->remove($lwdHasDisability);
						$message = "Disability/Special need record removed";
						$messageType = 'recordRemovedMessage';
					}
					else{
						$lwdHasDisability->setIdentifiedBy($formData['identified_by']);
			    		$lwdHasDisability->setIdentificationDate($formData['identification_date']);
			    		$lwdHasDisability->setCaseDescription($formData['case_description']);
			    		$lwdHasDisability->setIdlevel($em->getReference('AppBundle:Level', $formData['idlevel']));
			    		$em->persist($lwdHasDisability);
			    		$message = "Disability/Special need record updated";
			    		$messageType = $formCounter;
					}
					$em->flush();

					$this->addFlash($messageType, $message);
					return $this->redirectToRoute('edit_learner_disability', ['learnerId'=>$learnerId,'emisCode'=>$emisCode], 301);
    			}
    			$needForm = $needForms[$formCounter-1];
    			$needForm->handleRequest($request);
    			if($needForm->isValid()){
    				$formData = $needForm->getData();
    				$dataConverter = $this->get('data_converter');
    				$selectedNeeds = $dataConverter->arrayRemoveQuotes($formData['needs']);    				
    				$commaString = $dataConverter->convertToCommaString($selectedNeeds); /*convert array 
    				of checked values to comma delimited string */
    				$connection->executeQuery('DELETE FROM lwd_has_disability_has_need WHERE iddisability = ? 
    					AND idlwd = ? AND idneed NOT IN (?)', array($formData['iddisability'], $learnerId, $commaString));/*delete all records in the db
    				that are not checked on the form*/
    				//write the records for needs available to this learner if the records do not already exist in the db
    				$writeNeeds = $connection->prepare('INSERT IGNORE INTO lwd_has_disability_has_need SET idlwd = ?, 
    					iddisability = ?, idneed = ?');
    				$writeNeeds->bindParam(1, $learnerId);
    				$writeNeeds->bindParam(2, $formData['iddisability']);
    				//iterate over array of needs checked on the form and add each one to the database
    				foreach($selectedNeeds as $selectedNeed){
    					$writeNeeds->bindParam(3, $selectedNeed);
    					$writeNeeds->execute();
    				}
    				$writeNeeds->closeCursor();
    				$messageType = 'needs_'.$formCounter;
    				$message = "Available needs for this learner have been updated";
    				$this->addFlash($messageType, $message);
					return $this->redirectToRoute('edit_learner_disability', ['learnerId'=>$learnerId,'emisCode'=>$emisCode], 301);

    			}
    			$formCounter++;
    		}
    	}

    	//the form for adding a new disability to this learner's profile

    	//$

    	$levels2 = array();
    	if($this->get('session')->getFlashBag()->has('levels')){
    		$levels2 = $this->get('session')->getFlashBag()->get('levels');
    	}
    	$newForm = $this->createForm(new LearnerDisabilityType($disabilities, $levels2, "",false));

    	$newForm->handleRequest($request);
    	if($newForm->isValid()){
    		$em = $this->getDoctrine()->getManager();
    		$formData = $newForm->getData();
    		$lwdHasDisability = new LwdHasDisability();
    		$lwdHasDisability->setIdlwd($em->getReference('AppBundle:Lwd', $learnerId));
    		$idDisability = $this->getDoctrine()->getRepository('AppBundle:Disability')->findOneByIddisability($formData['iddisability']);
    		$lwdHasDisability->setIddisability($idDisability);
    		$lwdHasDisability->setIdentifiedBy($formData['identified_by']);
    		$lwdHasDisability->setIdentificationDate($formData['identification_date']);
    		$lwdHasDisability->setCaseDescription($formData['case_description']);
    		if($this->get('session')->getFlashBag()->has('levels')){
	    		$lwdHasDisability->setIdlevel($em->getReference('AppBundle:DisabilityHasLevel', [
	    			'iddisability'=>$formData['iddisability'], 
	    			'idlevel' => $formData['idlevel']
	    			])
	    		);
	    	}
    		
    		$em->persist($lwdHasDisability);
    		$em->flush();

    		$message = "New disability/special need record added for student ".$learnerId;
    		$this->addFlash('disabilityAddedMessage', $message);
    		return $this->redirectToRoute('edit_learner_disability', ['learnerId'=>$learnerId,'emisCode'=>$emisCode], 301);
    	}

    	//create a view of each of the forms
    	foreach($forms as &$form){
    		$form = $form->createView();
    	}
    	foreach($needForms as &$needForm){
    		$needForm = $needForm->createView();
    	}

    	return $this->render('school/learners/edit_learner_disability.html.twig', array(
    		'forms' => $forms, 'needForms'=>$needForms, 'newform' => $newForm->createView())
    	);
    } 
    //controller called through ajax to autopopulate level select list
    /**
     * @Route("/populatelevels/{disabilityId}", name="populate_levels", requirements ={"iddisability":"\d+"}, condition="request.isXmlHttpRequest()", options={"expose":true})
     */
    public function populateLevelsAction($disabilityId){
    	$connection = $this->get('database_connection');
    	$levels = $connection->fetchAll("SELECT idlevel, level_name FROM disability_has_level NATURAL JOIN level 
    					WHERE iddisability = ?", array($disabilityId));
    	$html = '';
    	if($levels){
    		$this->get('session')->getFlashBag()->set('levels', $levels);
    		foreach($levels as $key => $level){
    			$html .= '<option value="'.$level['idlevel'].'">'.$level['level_name'].'</option>';
    		}
    	}
    	return new Response($html);
    }
     /**
     * @Route("/school/{emisCode}/learners/{learnerId}/performance/{record}", name="edit_learner_performance", requirements ={"learnerId":"new|\d+", "record":"update|add"}, defaults={"record":"update"})
     */
     public function editLearnerPerformanceAction(Request $request, $learnerId, $record, $emisCode){
     	$connection = $this->get('database_connection');
    	//if this is not a new record, then create some default data.
     	$action = "added";
     	$mode = "Editing last performance record";
     	$performanceRecord;

     	$defaultData = array();
    	if($learnerId != 'new' && $record == 'update'){//if we are not adding a new learner or a new performance record for an existing learner
    		//fetch the last record added for this learner
    		$last_record = $connection->fetchAll("SELECT * FROM performance WHERE idlwd = ? ORDER BY year DESC,
    			term DESC LIMIT 1", array($learnerId));
    		if($last_record){//if a previous record exists for this learner
    			$defaultData = $last_record[0];
    			$defaultData['year'] = new \DateTime($defaultData['year'].'-1-1');
    			$performanceRecord = $this->getDoctrine()->getRepository('AppBundle:Performance')->findOneByRecId($defaultData['rec_id']);
    			$action = "updated";
    		}else{
    			$performanceRecord = new Performance();
    			$mode = "Adding new performance record";
    		}

    	}else{
    		$performanceRecord = new Performance();
    		$mode = "Adding new performance record";
    	}
    	$message = "";

    	$form = $this->createForm(new LearnerPerformanceType(), $defaultData);
    	$form->handleRequest($request);

    	if($form->isValid()){
    		$formData = $form->getData();

    		$performanceRecord->setIdlwd($this->getDoctrine()->getRepository('AppBundle:Lwd')->findOneByIdlwd($learnerId));
    		$performanceRecord->setStd($formData['std']);
    		$performanceRecord->setYear($formData['year']->format('Y-m-d'));
    		$performanceRecord->setTerm($formData['term']);
    		$performanceRecord->setGrade($formData['grade']);
    		$performanceRecord->setTeachercomment($formData['teachercomment']);
    		$performanceRecord->setEmiscode($this->getDoctrine()->getRepository('AppBundle:School')->findOneByEmiscode($emisCode));

    		$em = $this->getDoctrine()->getManager();
    		$em->persist($performanceRecord);
    		$em->flush();
    		$message = "Performance record ".$action;
    		$this->addFlash('message',$message);
    		return $this->redirectToRoute('edit_learner_performance', array(
    			'form' => $form,
    			'emisCode'=> $emisCode,
    			'learnerId' => $learnerId,
    			),
    		301
    		);
    	}
    	return $this->render('school/learners/edit_learner_performance.html.twig',array(
    		'form' => $form->createView(),
    		'mode' => $mode,)
    	);
    }

    /**
	 *@Route("/school/{emisCode}/teachers", name="school_teachers", requirements={"emisCode":"\d+"}, options={"expose"= true})
	 */
    public function teacherAction($emisCode, Request $request){

    	return $this->render('school/specialist_teacher/teachers_main.html.twig');
    }

}

?>
