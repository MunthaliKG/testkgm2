<?php
/*this is the controller for producing reports for the school section*/
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use AppBundle\Form\Type\CustomReportType;

class ZoneReportController extends Controller{
	/**
	 *@Route("/zone/{idzone}/reports/basic", name="zone_snl", requirements={"idzone":"\d+"})
	 */
	public function zoneReportMainAction($idzone, Request $request){

		//sub-reports to include in the report
		$reports = [0=>"Preliminary counts",1=>"Summary of learners with special needs",2=>"Teaching and learning materials"];
		//available formats for the report
		$formats = [
			'html'=>'html', 
			'pdf'=>'pdf', 
			'excel'=>'excel'
			];

		//create the form for choosing which sub-form to include and the format of the final report
		$form = $this->createFormBuilder()
			->add('reports','choice', array(
				'label' => 'Include',
				'expanded' => true,
				'multiple' => true,
				'choices'=> $reports,
                                //'data'=> 0,
				'constraints' => array(new NotBlank(["message"=>"Please select atleast one option"])),
				))
			->add('format','choice', array(
				'label' => 'Format',
				'expanded' => true,
				'multiple' => false,
				'choices'=> $formats,
				'data' => 0,
				'constraints' => array(new NotBlank(["message"=>"Please select a format"])),
				))
			->add('produce','submit', array("label" => "Produce report"))
			->getForm();

		$form->handleRequest($request);
		if($form->isValid()){
			$connection = $this->get('database_connection');
			$formData = $form->getData();
			$options = array(); //list of options to pass to the template
                        
                        $zone =  $connection->fetchAll('SELECT idzone, zone_name, iddistrict, district_name FROM zone, district '
                            . 'WHERE iddistrict = district_iddistrict and idzone = ?', [$idzone]);
                      
                        $options['zone'] = $zone[0];
                       
			//$options['school'] = $school;
                        $dataConverter = $this->get('data_converter');
                        $sntLatestYr = $connection->fetchAssoc('SELECT MAX(year) AS yr FROM school_has_snt NATURAL JOIN school NATURAL JOIN zone WHERE idzone = ?',[$idzone]);
                        $sntLastYr = $sntLatestYr['yr'] - 1;
                        $lwdLatestYr = $connection->fetchAssoc('SELECT MAX(year) AS yr FROM lwd_belongs_to_school NATURAL JOIN school NATURAL JOIN zone WHERE idzone = ?',[$idzone]);
                        $lwdLastYr = $lwdLatestYr['yr'] - 1;
                        $options['chaka'] = $lwdLatestYr['yr'];
                        //schools in a zone
                        $schoolsInZone = $connection->fetchAll('select emiscode, idzone from school where idzone =?', [$idzone]);                               
                        $options['numOfSchools'] = $dataConverter->countArray($schoolsInZone, 'idzone', $idzone);//get the number of schools		
        
			/*Preliminary counts section*/
			$learners = array();
			
			if(in_array(0, $formData['reports'])){ //if the preliminary counts option was checked
				$options['preliminary'] = true;

                                //learner preliminary counts                                
                                
				$learnersLatestYr = $connection->fetchAll('SELECT * FROM lwd_has_disability '
                                        . 'NATURAL JOIN lwd NATURAL JOIN lwd_belongs_to_school NATURAL JOIN school '
                                        . 'NATURAL JOIN zone WHERE idzone = ? and year = ?', [$idzone, $lwdLatestYr['yr']]);
                                                           
                                $learnersLastYr = $connection->fetchAll('SELECT * FROM lwd_has_disability '
                                        . 'NATURAL JOIN lwd NATURAL JOIN lwd_belongs_to_school NATURAL JOIN school '
                                        . 'NATURAL JOIN zone WHERE idzone = ? and year = ?', [$idzone, $lwdLastYr]);
				
                                //Learners by sex
                                $options['numBoys'] = $dataConverter->countArray($learnersLatestYr, 'sex', 'M');//get the number of boys
				$options['numGirls'] = $dataConverter->countArray($learnersLatestYr, 'sex', 'F');//get the number of girls
                                
                                $options['numBoysLY'] = $dataConverter->countArray($learnersLastYr, 'sex', 'M');//get the number of boys
				$options['numGirlsLY'] = $dataConverter->countArray($learnersLastYr, 'sex', 'F');//get the number of girls

                                //total enrolments
				$options['totalEnrolled'] = $options['numBoys'] + $options['numGirls'];
                                $options['totalEnrolledLastYear'] = $options['numBoysLY'] + $options['numGirlsLY'];
                        
                                //snt preliminary counts
				$teachers = $connection->fetchAll('SELECT * FROM snt NATURAL JOIN school_has_snt NATURAL JOIN school NATURAL JOIN zone WHERE idzone = ? and year = ?', [$idzone, $sntLatestYr['yr']]);
                                
				$options['sntMale'] = $dataConverter->countArray($teachers, 's_sex', 'M');
				$options['sntFemale'] = $dataConverter->countArray($teachers, 's_sex', 'F');
                                
                                $options['degree'] = $dataConverter->countArray($teachers, 'qualification', 'degree');
                                $options['certificate'] = $dataConverter->countArray($teachers, 'qualification', 'certificate');
                                $options['diploma'] = $dataConverter->countArray($teachers, 'qualification', 'diploma');
                                
                                $options['VI'] = $dataConverter->countArray($teachers, 'speciality', 'VI');
                                $options['HI'] = $dataConverter->countArray($teachers, 'speciality', 'HI');
                                $options['LD'] = $dataConverter->countArray($teachers, 'speciality', 'LD');
                                
                                $options['sntResident'] = $dataConverter->countArray($teachers, 'snt_type', 'Resident');
                                $options['sntItinerant'] = $dataConverter->countArray($teachers, 'snt_type', 'Itinerant');
                                
                                //classroom preliminary counts
                                $classPopulations = $connection->fetchAll('SELECT std, COUNT(DISTINCT(idlwd)) as numLearners FROM lwd_belongs_to_school 
                                        NATURAL JOIN lwd NATURAL JOIN school NATURAL JOIN zone WHERE idzone = ? AND `year` = ? GROUP BY std', [$idzone, $lwdLatestYr['yr']]);
                                $options['maxLearners'] = $dataConverter->findArrayMax($classPopulations, 'numLearners');
                                $options['minLearners'] = $dataConverter->findArrayMin($classPopulations, 'numLearners');

                                //room state preliminary counts
                                $rooms = $connection->fetchAll('SELECT room_id,enough_light,enough_space,enough_ventilation,adaptive_chairs,room_type,`access` 
                                        FROM room_state NATURAL JOIN school NATURAL JOIN zone WHERE idzone = ? AND `year` = ?', [$idzone, $lwdLatestYr['yr']]);
                                $options['rmTotal'] = count($rooms);
                                $options['rmEnoughLight'] = $dataConverter->countArray($rooms, 'enough_light', 'Yes');
                                $options['rmEnoughSpace'] = $dataConverter->countArray($rooms, 'enough_space', 'Yes');
                                $options['rmEnoughVent'] = $dataConverter->countArray($rooms, 'enough_ventilation', 'Yes');
                                $options['rmAdaptiveChairs'] = $dataConverter->countArray($rooms, 'adaptive_chairs', 'Yes');
                                $options['rmAccessible'] = $dataConverter->countArray($rooms, 'access', 'Yes');
                                $options['rmTemporary'] = $dataConverter->countArray($rooms, 'room_type', 'Temporary');
                                $options['rmPermanent'] = $options['rmTotal'] - $options['rmTemporary'];			

                                
			}
			/*End of preliminary counts section*/
                        
                        /*Start of Summary of learners with special needs*/
                        if(in_array(1, $formData['reports'])){ //if the Summary of learners with special needs option was checked
                            $options['specialNeeds'] = true;
                            $learnersTransOut = $connection->fetchall('select trans.* '
                                        . 'from (SELECT lastYr.* '
                                            . 'from (SELECT * FROM lwd NATURAL JOIN lwd_belongs_to_school NATURAL JOIN school NATURAL JOIN zone WHERE idzone = ? and year = ?) as lastYr '
                                            . 'left outer join (SELECT * FROM lwd NATURAL JOIN lwd_belongs_to_school NATURAL JOIN school NATURAL JOIN zone WHERE idzone = ? and year = ?) as thisYr '
                                            . 'on (lastYr.idlwd = thisYr.idlwd) where thisYr.idlwd IS NULL) as trans '
                                        . 'left outer join (select * from school_exit where year = ?) as exits ' //check if learner exists in school_exit
                                        . 'on (trans.idlwd = exits.idlwd) '
                                        . 'where exits.idlwd IS NULL', [$idzone, $lwdLastYr, $idzone, $lwdLatestYr['yr'],$lwdLatestYr['yr']]);
                                
                                $learnersTransIn = $connection->fetchall('select trans.* '
                                        . 'from (SELECT lastYr.* '
                                            . 'from (SELECT * FROM lwd NATURAL JOIN lwd_belongs_to_school NATURAL JOIN school NATURAL JOIN zone WHERE idzone = ? and year = ?) as lastYr '
                                            . 'left outer join (SELECT * FROM lwd NATURAL JOIN lwd_belongs_to_school NATURAL JOIN school NATURAL JOIN zone WHERE idzone = ? and year = ?) as thisYr '
                                            . 'on (lastYr.idlwd = thisYr.idlwd) where thisYr.idlwd IS NULL) as trans '
                                        . 'left outer join (select * from school_exit where year = ?) as exits ' //check if learner exists in school_exit
                                        . 'on (trans.idlwd = exits.idlwd) '
                                        . 'where exits.idlwd IS NULL', [$idzone, $lwdLatestYr['yr'], $idzone, $lwdLastYr, $lwdLatestYr['yr']]);
                                
                                $learnersDropouts = $connection->fetchall('select dropouts.* from 
                                    (SELECT * FROM lwd NATURAL JOIN lwd_belongs_to_school NATURAL JOIN school NATURAL JOIN zone 
                                        WHERE idzone = ? and year = ?) as dropouts, school_exit as exits
                                    where dropouts.idlwd = exits.idlwd and exits.reason <> \'completed\' and exits.year = ?',[$idzone, $lwdLatestYr['yr'], $lwdLatestYr['yr']]);
                                
                                $learnersCompleted = $connection->fetchall('select dropouts.* from 
                                    (SELECT * FROM lwd NATURAL JOIN lwd_belongs_to_school NATURAL JOIN school NATURAL JOIN zone 
                                        WHERE idzone = ? and year = ?) as dropouts, school_exit as exits
                                    where dropouts.idlwd = exits.idlwd and exits.reason = \'completed\' and exits.year = ?',[$idzone, $lwdLatestYr['yr'], $lwdLatestYr['yr']]);
                                
                            //transfers out
                                $options['numBoysTRout'] = $dataConverter->countArray($learnersTransOut, 'sex', 'M');
                                $options['numGirlsTRout'] = $dataConverter->countArray($learnersTransOut, 'sex', 'F');
                                $options['totalTransferOut'] =  $options['numBoysTRout'] + $options['numGirlsTRout'];
                                
                                //transfer in
                                $options['numBoysTRin'] = $dataConverter->countArray($learnersTransIn, 'sex', 'M');
                                $options['numGirlsTRin'] = $dataConverter->countArray($learnersTransIn, 'sex', 'F');
                                $options['totalTransferIn'] =  $options['numBoysTRin'] + $options['numGirlsTRin'];
                                
                                //dropouts
                                $options['numBoysDO'] = $dataConverter->countArray($learnersDropouts, 'sex', 'M'); 
                                $options['numGirlsDO'] = $dataConverter->countArray($learnersDropouts, 'sex', 'F');
                                $options['totalDropouts'] =  $options['numBoysDO'] + $options['numGirlsDO'];
                                
                                //completed
                                $options['numBoysC'] = $dataConverter->countArray($learnersCompleted, 'sex', 'M'); 
                                $options['numGirlsC'] = $dataConverter->countArray($learnersCompleted, 'sex', 'F');
                                $options['totalCompleted'] =  $options['numBoysC'] + $options['numGirlsC'];                                                      
                                      
                            //learners by std, sex and age - STARTS HERE                            
                                $learnersBySexAgeStd = array();
                                $learnersBy = array();
                                $totalStdSexAge = array();
                                $gender = array('M'=>'M','F'=>'F');
                                $ages = array('<6'=>5, '6'=>6, '7'=>7, 
                                    '8'=>8, '9'=>9, '10'=>10,'11'=>11,'12'=>12,
                                    '13'=>13,'14'=>14,'15'=>15,'16'=>16,'17'=>17,'>17'=>18);
                                $stds = array('1'=>1, '2'=>2,'3'=>3,'4'=>4,'5'=>5,'6'=>6,'7'=>7,'8'=>8);
                                $learnersBySexAgeStd = $connection->fetchAll('select DISTINCT idlwd, iddistrict, idzone, sex, dob, round(datediff(?,dob)/365) as age, std, emiscode, year '
                                    .'from lwd NATURAL JOIN performance NATURAL JOIN school NATURAL JOIN zone '
                                    .'where idzone = ? and year = ?', [$lwdLatestYr['yr'].'-01-01', $idzone,$lwdLatestYr['yr']]);
                                
                                $counterStdSex = array();
                                $counterStdBySex = array();
                                
                                //obtain the counter and sums for age by sex
                                foreach ($ages as $key => $age) {
                                    $counterAgeBySex[$key]['M'] = 0;
                                    $counterAgeBySex[$key]['F'] = 0;
                                    foreach ($stds as $std) {                                                                            
                                        foreach ($gender as $sex) {
                                            if ($key == '<6'){
                                                $learnersBy[$key][$std][$sex] = $dataConverter->countArrayMultipleBool($learnersBySexAgeStd, ['age'=>$key, 'std'=>' == '.$std, 'sex'=>' == \''.$sex.'\'']);                                                   
                                            }elseif($key == '>17'){                                              
                                                $learnersBy[$key][$std][$sex] = $dataConverter->countArrayMultipleBool($learnersBySexAgeStd, ['age'=>$key, 'std'=>' == '.$std, 'sex'=>' == \''.$sex.'\'']);
                                            }else{                                                    
                                                $learnersBy[$key][$std][$sex] = $dataConverter->countArrayMultiple($learnersBySexAgeStd, ['age'=>$age, 'std'=>$std, 'sex'=>$sex]);
                                            }
                                            //get totals for across age and standards by sex
                                            if ($sex == 'M'){
                                                $counterAgeBySex[$key]['M'] = $counterAgeBySex[$key]['M'] + $learnersBy[$key][$std][$sex];
                                            }else {
                                                $counterAgeBySex[$key]['F'] = $counterAgeBySex[$key]['F'] + $learnersBy[$key][$std][$sex];
                                            }
                                        }                                     
                                    }
                                }
                                //flip the array to sum downwards for std by sex
                                foreach ($stds as $std) {                                    
                                    $counterStdBySex[$std]['M'] = 0;
                                    $counterStdBySex[$std]['F'] = 0;
                                    foreach ($ages as $key => $age) {                                        
                                        foreach ($gender as $sex) {                                            
                                            //get totals for across age and standards by sex
                                            if ($sex == 'M'){
                                                $counterStdBySex[$std]['M'] =  $counterStdBySex[$std]['M'] + $learnersBy[$key][$std][$sex];
                                            }else {
                                                $counterStdBySex[$std]['F'] =  $counterStdBySex[$std]['F'] + $learnersBy[$key][$std][$sex];
                                            }
                                        }                                     
                                    }
                                }
                               $options['stdBySex'] = $counterStdBySex;
                                $options['ageBySex'] = $counterAgeBySex; 
                               //learners by std, sex and age - ENDS HERE
                            $options['learnersBy'] = $learnersBy;
                        }
                        /*End of Summary of learners with special needs*/
                        
                        /*Start of Teaching and learning materials*/
                      /*Start of Teaching and learning materials*/
                        if(in_array(2, $formData['reports'])){ //if the Teaching and learning materials option was checked
                            $options['learningMaterials'] = true;
                            //learners needs - STARTS HERE
                            $learnersNeeds = $connection->fetchAll('SELECT needname, SUM(school_has_need.quantity_available) as quantity_available, '
                                    . 'SUM(school_has_need.quantity_in_use) as quantity_in_use, '
                                    . 'sum(school_has_need.quantity_required) as quantity_required '
                                    . 'FROM school_has_need NATURAL JOIN school NATURAL JOIN need NATURAL JOIN zone '
                                    . 'WHERE idzone = ? and school_has_need.year_recorded = ? GROUP BY needname', [$idzone, $lwdLatestYr['yr']]);                                                               
                            //learners needs by resource room or not - ENDS HERE
                            $options['teachingNeeds'] = $learnersNeeds;                           
                        }
                        /*End of Teaching and learning materials*/

			$productionDate = new \DateTime(date('Y-m-d H:i:s'));
			$options['date'] = $productionDate;
			if($formData['format'] == 'html' || $formData['format'] == 'pdf'){
				$isHtml = ($formData['format'] == 'html')? true: false;
				$options['isHtml'] = $isHtml;
				$html = $this->renderView('zone/reports/aggregate_zone_report.html.twig', $options);
				if($isHtml){
					return new Response($html);
				}else{
					$mpdfService = $this->get('tfox.mpdfport');
					$arguments = ['outputFileName'=>$zone[0]['zone_name'].'_zone_report.pdf', 'outputDest'=>"I"];
					$response = $mpdfService->generatePdfResponse($html, $arguments);
					$response->headers->set('Content-Disposition','inline; filename = '.$arguments['outputFileName']);
					$response->headers->set('Content-Transfer-Encoding','binary');
					$response->headers->set('Accept-Ranges','bytes');
					return $response;
					exit;
				}
			}                        			
		}

		return $this->render('zone/reports/zone_reports_basic.html.twig', array('form' => $form->createView()));
	}
        
        /**
	 *@Route("/zone/{idzone}/reports/custom", name="zone_custom_snl", requirements={"idzone":"\d+"})
         */
	public function zoneCustomReportAction($idzone, Request $request){

		//sub-reports to include in the report
		$reports = [0=>"SN Learners' details",1=>"SN Teachers details"];
                $enrollments = [0=>"Class & Gender", 1=>"Disability Category & Gender", 2=>"Disability & Gender"];
		//available formats for the report
		$formats = [
			'html'=>'html', 
			'pdf'=>'pdf', 
			'excel'=>'excel'
			];

		//create the form for choosing which sub-form to include and the format of the fina report
		$form = $this->createFormBuilder()
			->add('reports','choice', array(
				'label' => 'Include',
				'expanded' => true,
				'multiple' => true,
				'choices'=> $reports,
				'constraints' => array(new NotBlank(["message"=>"Please select atleast one option"])),
				))
                        ->add('enrollments','choice', array(
				'label' => 'Enrollement by:',
				'expanded' => true,
				'multiple' => true,
				'choices'=> $enrollments,
				'constraints' => array(new NotBlank(["message"=>"Please select atleast one option"])),
				))
			->add('format','choice', array(
				'label' => 'Format',
				'expanded' => true,
				'multiple' => false,
				'choices'=> $formats,
				'data' => 0,
				'constraints' => array(new NotBlank(["message"=>"Please select a format"])),
				))
			->add('produce','submit', array('label' => "Produce report"))
			->getForm();

		$form->handleRequest($request);                                
                
		if($form->isValid()){
			$connection = $this->get('database_connection');
			$formData = $form->getData();
			$options = array(); //list of options to pass to the template
			$zone =  $connection->fetchAll('SELECT idzone, zone_name, iddistrict, district_name FROM zone, district '
                            . 'WHERE iddistrict = district_iddistrict and idzone = ?', [$idzone]);
                        $dataConverter = $this->get('data_converter');
                        $options['zone'] = $zone[0];
                        $schoolsInZone = $connection->fetchAll('select emiscode, idzone from school where idzone =?', [$idzone]);                               
                        $options['numOfSchools'] = $dataConverter->countArray($schoolsInZone, 'idzone', $idzone);//get the number of schools

			$learners = array();			
			if(in_array(1, $formData['reports']) || in_array(0, $formData['reports'])){

                            //get the latest year from the lwd_belongs to school table
                            $yearQuery = $connection->fetchAssoc('SELECT MAX(year) AS yr FROM lwd_belongs_to_school NATURAL JOIN school NATURAL JOIN zone '
                                    . 'WHERE idzone = ?',[$idzone]);
                                    //$connection->fetchAssoc('SELECT MAX(`year`) as maxYear FROM lwd_belongs_to_school 
                                    //WHERE emiscode = ?', [$emisCode]);                           		
        
                            /*SN learners' details section*/
                            if(in_array(0, $formData['reports'])){//if the SN learners' details option was checked
                                    $options['snLearners'] = true;
                                    //get students enrolled this year
                                    $enrolled = $connection->fetchAll('SELECT first_name, last_name, home_address, sex, dob, 
                                        distance_to_school, gfirst_name, glast_name, gsex, occupation, household_type 
                                        FROM lwd NATURAL JOIN guardian NATURAL JOIN lwd_belongs_to_school NATURAL JOIN school
                                        WHERE idzone = ? AND `year` = ?', 
                                            [$idzone, $yearQuery['yr']]);				
                                    $options['snLearners'] = $enrolled;    
				}
                            if(in_array(1, $formData['reports'])){//if the SN teachers' details option was checked
                                    $options['snTeachers'] = true;
                                    //get teachers this year
                                    $employed = $connection->fetchAll('SELECT sfirst_name, employment_number,slast_name, 
                                        s_sex, s_dob, qualification, speciality, year_started, teacher_type 
                                        FROM snt NATURAL JOIN school_has_snt NATURAL JOIN school
                                        WHERE idzone = ? AND `year` = ?', 
                                            [$idzone, $yearQuery['yr']]);				
                                    $options['snTeachers'] = $employed;    
				}
			}	
                        
                        //enrollments stuff start here
                        //get the latest year from the lwd_belongs to school table
                        $yearQuery = $connection->fetchAssoc('SELECT MAX(`year`) as maxYear FROM lwd_belongs_to_school NATURAL JOIN school
                                        WHERE idzone = ?', [$idzone]);
                        $gender = array('M'=>'M','F'=>'F');
                        $learnersBySex = array();
                        $total;
                        /*SN learners' details section*/
                        if(in_array(0, $formData['enrollments'])){//if by class and sex option was checked
                            $options['lwdBCG'] = true;
                            //get students enrolled this year
                            $enrolled = $connection->fetchAll('SELECT idlwd, std, sex, idzone '
                                    . 'FROM lwd NATURAL JOIN lwd_belongs_to_school NATURAL JOIN school '
                                    . 'WHERE idzone = ? AND `year` = ?',[$idzone, $yearQuery['maxYear']]);				

                            $enrolledCount = $dataConverter->countArray($enrolled, 'idzone', $idzone);                                
                            $stds = array('1'=>1, '2'=>2,'3'=>3,'4'=>4,'5'=>5,'6'=>6,'7'=>7,'8'=>8);

                            $learnersByClassSex = array();                            
                            $learnersByClass = array();
                             //initialise arrays
                            foreach ($gender as $key => $gValue) {
                                foreach ($stds as $key => $std) {                                                                                    
                                    $learnersByClassSex[$gValue][$std] = 0;
                                    $learnersByClass[$std] = 0;
                                }
                                $learnersBySex[$gValue] = 0;
                            }                             
                            //loop through the selection list counting the gender, std combinations                                   
                            foreach ($gender as $key => $gValue) {
                                foreach ($stds as $key => $std) {                                                                                            
                                    $learnersByClassSex[$gValue][$std] = $dataConverter->countArrayMultiple($enrolled, ['sex'=>$gValue, 'std'=>$std]);
                                    $learnersBySex[$gValue] = $learnersBySex[$gValue] + $learnersByClassSex[$gValue][$std];
                                }
                            }
                            //sum learners per class
                            foreach ($stds as $key => $std){
                                foreach ($gender as $key => $gValue){
                                    $learnersByClass[$std] = $learnersByClass[$std] + $learnersByClassSex[$gValue][$std];
                                }
                            }
                            $total = 0;
                            //flip and sum per class
                            foreach ($stds as $key => $std) { 
                                foreach ($gender as $key => $gValue) {
                                    //$learnersByClass[$std] = $learnersByClass[$std] 
                                    $total = $total + $learnersByClassSex[$gValue][$std];
                                    //$total = $total + $learnersByClass[$std];
                                }
                            }
                            $options['learnersBCGT'] = $total;
                            $options['learnersBCG'] = $learnersByClassSex;
                            $options['learnersBG'] = $learnersBySex;
                            $options['learnersBC'] = $learnersByClass;
                        }
                        if(in_array(1, $formData['enrollments'])){//if disability category and sex was checked
                            $options['lwdBIG'] = true;
                            $dbCategories = $connection->fetchAll('SELECT iddisability_category, category_name FROM disability_category');
                            $categories = array();
                            foreach ($dbCategories as $key => $row) {
                                $categories[$row['iddisability_category']] = $row['category_name'];
                            }                            
                            //get teachers this year
                            $enrolled = $connection->fetchAll('SELECT idlwd, category_name, sex, idzone '
                                    . 'FROM lwd NATURAL JOIN lwd_belongs_to_school NATURAL JOIN lwd_has_disability'
                                    . ' NATURAL JOIN disability NATURAL JOIN disability_category NATURAL JOIN school'
                                    . ' WHERE idzone = ? AND `year` = ?',[$idzone, $yearQuery['maxYear']]);				
                            
                             $learnersByCategorySex = array();                           
                             $learnersByCategory = array();
                             //initialise array
                            foreach ($categories as $key => $category) {
                                foreach ($gender as $key => $gValue) {                                                                                                                    
                                    $learnersByCategorySex[$category][$gValue] = 0;
                                    $learnersBySex[$gValue] = 0; 
                                }
                                $learnersByCategory[$category] =0;
                            }                            
                            //loop through the selection list counting the gender, std combinations                                   
                           foreach ($categories as $key => $category) {
                                foreach ($gender as $key => $gValue) {                                                                                                                    
                                    $learnersByCategorySex[$category][$gValue] = $dataConverter->countArrayMultiple($enrolled, ['sex'=>$gValue, 'category_name'=>$category]);
                                    $learnersByCategory[$category] = $learnersByCategory[$category] + $learnersByCategorySex[$category][$gValue];                                   
                                }
                            }
                            //sum by sex across the disability categories
                            foreach ($gender as $key => $gValue) {
                                foreach ($categories as $key => $category) {
                                    $learnersBySex[$gValue] = $learnersBySex[$gValue] + $learnersByCategorySex[$category][$gValue];
                                }
                            }
                            $total = 0;
                             //flip and sum per class
                            foreach ($gender as $key => $gValue) {
                                foreach ($categories as $key => $category) {                                
                                    //$learnersBySex[$gValue] = $learnersBySex[$gValue] + 
                                    $total = $total + $learnersByCategorySex[$category][$gValue];                                   
                                }
                            }                            
                            $options['learnersBIGT'] = $total;
                            $options['learnersBS1'] = $learnersBySex;
                            $options['learnersBC1'] = $learnersByCategory;
                            $options['learnersBIG'] = $learnersByCategorySex;    
                        }
                        if(in_array(2, $formData['enrollments'])){//if disability and sex was checked
                            $options['lwdBDG'] = true;
                            $dbDisabilities = $connection->fetchAll('SELECT iddisability, disability_name FROM disability');
                            $disabilities = array();
                            foreach ($dbDisabilities as $key => $row) {
                                $disabilities[$row['iddisability']] = $row['disability_name'];
                            }                            
                            //get teachers this year
                            $enrolled = $connection->fetchAll('SELECT idlwd, disability_name, sex, idzone '
                                    . 'FROM lwd NATURAL JOIN lwd_belongs_to_school NATURAL JOIN lwd_has_disability'
                                    . ' NATURAL JOIN disability NATURAL JOIN school'
                                    . ' WHERE idzone = ? AND `year` = ?',[$idzone, $yearQuery['maxYear']]);				
                            
                             $learnersByDisabilitySex = array();                             
                             $learnersByDisability = array();
                             //initialise arrays
                            foreach ($disabilities as $key => $disability) {
                                foreach ($gender as $key => $gValue) {                                                                                                                    
                                    $learnersByDisabilitySex[$disability][$gValue] = 0;
                                    $learnersBySex[$gValue] = 0; 
                                }
                                $learnersByDisability[$disability] = 0;
                            }

                            //loop through the selection list counting the gender, std combinations                                   
                            foreach ($disabilities as $key => $disability) {
                                foreach ($gender as $key => $gValue) {                                                                                                                     
                                    $learnersByDisabilitySex[$disability][$gValue] = $dataConverter->countArrayMultiple($enrolled, ['sex'=>$gValue, 'disability_name'=>$disability]);
                                    $learnersByDisability[$disability] = $learnersByDisability[$disability] + $learnersByDisabilitySex[$disability][$gValue];                                    
                                }
                            }
                            $total = 0;
                            //flip and sum per class
                            foreach ($gender as $key => $gValue) {
                                foreach ($disabilities as $key => $disability) {                                
                                    $learnersBySex[$gValue] = $learnersBySex[$gValue] + $learnersByDisabilitySex[$disability][$gValue];
                                    $total = $total + $learnersByDisabilitySex[$disability][$gValue];
                                    //$total = $total + $learnersBySex[$gValue];
                                }
                            }
                            $options['learnersBDGT'] = $total;
                            $options['learnersBS2'] = $learnersBySex;
                            $options['learnersBD'] = $learnersByDisability;
                            $options['learnersBDG'] = $learnersByDisabilitySex;    
                        }
                        //enrollments stuff end here
			$productionDate = new \DateTime(date('Y-m-d H:i:s'));
			$options['date'] = $productionDate;
			if($formData['format'] == 'html' || $formData['format'] == 'pdf'){
                            $isHtml = ($formData['format'] == 'html')? true: false;
                            $options['isHtml'] = $isHtml;
                            $html = $this->renderView('zone/reports/aggregate_zone_custom.html.twig', $options);
                            if($isHtml){
                                return new Response($html);
                            }else{
                                $mpdfService = $this->get('tfox.mpdfport');
                                $arguments = ['outputFileName'=>'report.pdf', 'outputDest'=>"I"];
                                $response = $mpdfService->generatePdfResponse($html, $arguments);
                                $response->headers->set('Content-Disposition','inline; filename = '.$arguments['outputFileName']);
                                $response->headers->set('Content-Transfer-Encoding','binary');
                                $response->headers->set('Accept-Ranges','bytes');
                                return $response;
                                exit;
                            }
			}else{
                            $dataConverter = $this->get('data_converter');
                            $enrolled = $connection->fetchAll('SELECT first_name, last_name, initials, home_address, sex, dob, 
                                        distance_to_school, gfirst_name, glast_name, gsex, occupation, income_level 
                                        FROM lwd NATURAL JOIN guardian NATURAL JOIN lwd_belongs_to_school 
                                        WHERE idzone = ? AND `year` = ?', 
                                            [$idzone, $yearQuery['maxYear']]);                     
                        } 		
			
		}                               

		return $this->render('zone/reports/zone_reports_custom.html.twig', array('form' => $form->createView()));
    }
}
?>

