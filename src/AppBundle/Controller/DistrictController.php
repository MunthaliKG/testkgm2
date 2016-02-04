<?php
/*this is the controller for the zone page
*it controls all links starting with zone/
*/

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\Type\LearnerTransferType;
use AppBundle\Form\Type\LwdFinderTypeType;

class DistrictController extends Controller{
    /**
     *@Route("/district/{iddistrict}", name="district_main", requirements={"iddistrict":"\d+"})
     */
    
    public function districtMainAction($iddistrict, Request $request){

        $connection = $this->get('database_connection');
        $district =  $connection->fetchAll('SELECT * FROM district '
                . 'WHERE iddistrict = ?',array($iddistrict));

        $year = $connection->fetchAssoc("SELECT year FROM lwd_belongs_to_school ORDER BY year DESC");

        $sumquery = 'SELECT count(iddisability) FROM lwd 
            NATURAL JOIN lwd_has_disability NATURAL JOIN disability NATURAL JOIN lwd_belongs_to_school NATURAL JOIN school
            WHERE iddistrict = ? AND year = ?';
        //disabilities in a district
        $disabilities = $connection->fetchAll("SELECT disability_name, count(iddisability) as num_learners,($sumquery) as total 
            FROM lwd NATURAL JOIN lwd_has_disability NATURAL JOIN disability NATURAL JOIN lwd_belongs_to_school NATURAL JOIN school
            WHERE iddistrict = ? AND year = ? GROUP BY iddisability", array($iddistrict,$year['year'],$iddistrict,$year['year']));
        
        //schools in a district
        $schoolsInDistrict = $connection->fetchAll('select emiscode, iddistrict from school where iddistrict =?', [$iddistrict]);
        $dataConverter = $this->get('data_converter');
        $numOfSchools = $dataConverter->countArray($schoolsInDistrict, 'iddistrict', $iddistrict);//get the number of schools		
        
        $session = $request->getSession();
        //keep the emiscode of the selected zone in the session so we can always redirect to it until the next school is chosen
        $session->set('iddistrict', $iddistrict);
        
        //keep the name of the selected zone in the session to access it from the school selection form
        $session->set('district_name', $district[0]['district_name']);
        
        //keep zone information
        $session->set('districtInfo', $district[0]);

        return $this->render('district/district2.html.twig',
                array('district' => $district[0],
                        'disabilities' => $disabilities,
                    'numOfSchools' => $numOfSchools,
                    'year' => $year['year'])
                );
    }
      /**
     * @Route("/school/{iddistrict}/learners/transfer", name="learner_transfer")
     */
    public function learnerTransferAction(Request $request, $iddistrict){
        $connection = $this->get('database_connection');      
        $district =  $connection->fetchAll('SELECT * FROM district '
                . 'WHERE iddistrict = ?',array($iddistrict));
        
        $em = $this->getDoctrine()->getManager();
        $fBag = $this->get('session')->getFlashBag();
              $learnerList = array();
        if($fBag->has('learnerList')){
            $learnerList = $fBag->get('learnerList');
        }
        $learners = array();
        foreach ($learnerList as $learner) {
            $learners[$learner['idlwd']] = $learner['idlwd'] . ': ' . $learner['first_name'] . ' ' . $learner['last_name'];
        }
        /*create the array to be used for storing the list of schools for the dynamically populated select element. 
        (set to an empty array if not found in the session)  */
        $schoolList = array();
        if($fBag->has('schoolList')){
            $schoolList = $fBag->get('schoolList');           
        }
        $schools = array();
        //print_r($schoolList);exit;
        foreach ($schoolList as $school) {
            $schools[$school->getEmiscode()] = $school->getSchoolName();
        }

        $schoolsTo = $connection->fetchAll('SELECT emiscode, school_name '
                . 'FROM school WHERE iddistrict = ?', array($iddistrict));
            
        $schoolsToChoices = array();
        foreach ($schoolsTo as $row) {
            $schoolsToChoices[$row['emiscode']] = $row['school_name'];
        }
        
        $form1 = $this->createForm(new \AppBundle\Form\Type\LwdFinderType($schools,$learners,$schoolsToChoices));
        $form1->handleRequest($request);      
        if($form1->isValid()){                       
            $formData = $form1->getData();
            //$m = $this->getDoctrine()->getManager();
            //echo $formData['std'];exit;
            $learnerPrevSchool = $connection->fetchAssoc("SELECT other_means, means_to_school FROM lwd_belongs_to_school"
                    . " WHERE idlwd = ? ORDER BY year DESC", array($formData['learner']));
            $lwdBTSchool = new \AppBundle\Entity\LwdBelongsToSchool();
            $lwdBTSchool->setEmiscode($em->getReference('AppBundle:School', ['emiscode'=>$formData['schoolTo']]));
            $lwdBTSchool->setYear($formData['year']->format('Y-m-d'));
            $lwdBTSchool->setDistanceToSchool($formData['distance_to_school']);
            $lwdBTSchool->setMeansToSchool($learnerPrevSchool['means_to_school']);
            $lwdBTSchool->setOtherMeans($learnerPrevSchool['other_means']);
            $lwdBTSchool->setIdlwd($em->getReference('AppBundle:Lwd', ['idlwd'=>$formData['learner']]));
            $lwdBTSchool->setStd($formData['std']);                         
                
            $em->persist($lwdBTSchool);
            $em->flush();
            
            $request->getSession()->getFlashBag()
                            ->add('transfer', 'Transfer for ('.$formData['learner'].') was successful');
            return $this->redirectToRoute('learner_transfer',['iddistrict'=>$iddistrict], 301);
         }
        
        
        return $this->render('district/transfer.html.twig', array(
        'learnerFinderForm'=>$form1->createView(),         
                ));
    }
    
    /**
     *@Route("/district/{iddistrict}/transfer/{learnerId}", name="district_transer", requirements={"iddistrict":"\d+"})
     */
    public function districtTransferAction($iddistrict, $learnerId, Request $request){
        
    }
}
?>