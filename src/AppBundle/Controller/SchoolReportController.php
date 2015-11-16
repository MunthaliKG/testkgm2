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

class SchoolReportController extends Controller{
	/**
	 *@Route("/school/{emisCode}/reports/basic", name="school_reports", requirements={"emisCode":"\d+"})
	 */
	public function schoolReportMainAction($emisCode, Request $request){

		//sub-reports to include in the report
		$reports = [0=>"Preliminary counts",1=>"Summary of learners with special needs",2=>"Teaching and learning materials"];
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
			$school = $connection->fetchAssoc('SELECT emiscode,school_name,iddistrict,district_name,idzone,zone_name 
				FROM school NATURAL JOIN district NATURAL JOIN zone WHERE emiscode = ?', [$emisCode]);
                        
			$options['school'] = $school;

			$learners = array();
			$dataConverter = $this->get('data_converter');
                        $sntLatestYr = $connection->fetchAssoc('SELECT MAX(year) AS yr FROM school_has_snt NATURAL JOIN school WHERE emiscode = ?',[$emisCode]);
				$sntLastYr = $sntLatestYr['yr'] - 1;
                                $lwdLatestYr = $connection->fetchAssoc('SELECT MAX(year) AS yr FROM lwd_belongs_to_school NATURAL JOIN school WHERE emiscode = ?',[$emisCode]);
				$lwdLastYr = $lwdLatestYr['yr'] - 1;
                                $options['chaka'] = $lwdLatestYr['yr'];
			if(in_array(1, $formData['reports']) || in_array(0, $formData['reports'])){

				//get the latest year from the lwd_belongs to school table
				$yearQuery = $connection->fetchAssoc('SELECT MAX(`year`) as maxYear FROM lwd_belongs_to_school 
					WHERE emiscode = ?', [$emisCode]);
				//learner preliminary counts
				$learners = $connection->fetchAll('SELECT DISTINCT(idlwd), sex, std, dob, round(datediff(?,dob)/365) as age
				  FROM lwd NATURAL JOIN lwd_belongs_to_school 
					WHERE emiscode = ? AND `year`=?', [$yearQuery['maxYear'].'-01-01', $emisCode, $yearQuery['maxYear']]);

				/*Preliminary counts section*/
				if(in_array(0, $formData['reports'])){ //if the preliminary counts option was checked
					$options['preliminary'] = true;

					$options['learnersTotal'] = count($learners);
					$options['numBoys'] = $dataConverter->countArray($learners, 'sex', "M");//get the number of boys
					$options['numGirls'] = $options['learnersTotal'] - $options['numBoys'];//get the number of girls

					//snt preliminary counts
					$latestYr = $connection->fetchAssoc('SELECT MAX(year) AS yr FROM school_has_snt 
						WHERE emiscode = ?',[$emisCode]);
					$teachers = $connection->fetchAll('SELECT * FROM school_has_snt NATURAL JOIN snt 
						WHERE `year` = ? AND emiscode = ?', [$latestYr['yr'], $emisCode]);
					$options['sntTotal'] = count($teachers);
					$options['sntMale'] = $dataConverter->countArray($teachers, 's_sex', 'M');
					$options['sntFemale'] = $options['sntTotal'] - $options['sntMale'];
					$options['sntItinerant'] = $dataConverter->countArray($teachers, 'snt_type', 'Itinerant');
					$options['sntResident'] = $options['sntTotal'] - $options['sntItinerant'];

					//classroom preliminary counts
					$classPopulations = $connection->fetchAll('SELECT std, COUNT(DISTINCT(idlwd)) as numLearners FROM lwd_belongs_to_school 
						NATURAL JOIN lwd WHERE emiscode = ? AND `year` = ? GROUP BY std', [$emisCode, $yearQuery['maxYear']]);
					$options['maxLearners'] = $dataConverter->findArrayMax($classPopulations, 'numLearners');
					$options['minLearners'] = $dataConverter->findArrayMin($classPopulations, 'numLearners');

					//room state preliminary counts
					$rooms = $connection->fetchAll('SELECT room_id,enough_light,enough_space,enough_ventilation,adaptive_chairs,room_type,`access` 
						FROM room_state WHERE emiscode = ? AND `year` = ?', [$emisCode, $yearQuery['maxYear']]);
					$options['rmTotal'] = count($rooms);
					$options['rmEnoughLight'] = $dataConverter->countArray($rooms, 'enough_light', 'Yes');
					$options['rmEnoughSpace'] = $dataConverter->countArray($rooms, 'enough_space', 'Yes');
					$options['rmEnoughVent'] = $dataConverter->countArray($rooms, 'enough_ventilation', 'Yes');
					$options['rmAdaptiveChairs'] = $dataConverter->countArrayBool($rooms, 'adaptive_chairs', '>0');
					$options['rmAccessible'] = $dataConverter->countArray($rooms, 'access', 'Yes');
					$options['rmTemporary'] = $dataConverter->countArray($rooms, 'room_type', 'Temporary');
					$options['rmPermanent'] = $options['rmTotal'] - $options['rmTemporary'];			
				}
				/*End of preliminary counts section*/

				/*Summary of learners with special needs section*/
				if(in_array(1, $formData['reports'])){//if the summary of learners with special needs option was checked
                                    $options['specialNeeds'] = true;
                                    $learnersTransOut = $connection->fetchall('select trans.* '
                                    . 'from (SELECT lastYr.* '
                                        . 'from (SELECT * FROM lwd NATURAL JOIN lwd_belongs_to_school NATURAL JOIN school WHERE emiscode = ? and year = ?) as lastYr '
                                        . 'left outer join (SELECT * FROM lwd NATURAL JOIN lwd_belongs_to_school NATURAL JOIN school WHERE emiscode = ? and year = ?) as thisYr '
                                        . 'on (lastYr.idlwd = thisYr.idlwd) where thisYr.idlwd IS NULL) as trans '
                                    . 'left outer join (select * from school_exit where year = ?) as exits ' //check if learner exists in school_exit
                                    . 'on (trans.idlwd = exits.idlwd) '
                                    . 'where exits.idlwd IS NULL', [$emisCode, $lwdLastYr, $emisCode, $lwdLatestYr['yr'],$lwdLatestYr['yr']]);
                                
                                    $learnersTransIn = $connection->fetchall('select trans.* '
                                            . 'from (SELECT lastYr.* '
                                                . 'from (SELECT * FROM lwd NATURAL JOIN lwd_belongs_to_school NATURAL JOIN school WHERE emiscode = ? and year = ?) as lastYr '
                                                . 'left outer join (SELECT * FROM lwd NATURAL JOIN lwd_belongs_to_school NATURAL JOIN school WHERE emiscode = ? and year = ?) as thisYr '
                                                . 'on (lastYr.idlwd = thisYr.idlwd) where thisYr.idlwd IS NULL) as trans '
                                            . 'left outer join (select * from school_exit where year = ?) as exits ' //check if learner exists in school_exit
                                            . 'on (trans.idlwd = exits.idlwd) '
                                            . 'where exits.idlwd IS NULL', [$emisCode, $lwdLatestYr['yr'], $emisCode, $lwdLastYr, $lwdLatestYr['yr']]);
                                
                                    //get students enrolled this year
                                    $enrolled = $connection->fetchAll('SELECT sex, year FROM lwd NATURAL JOIN lwd_belongs_to_school 
                                            WHERE emiscode = ? GROUP BY idlwd HAVING COUNT(idlwd) = 1 AND `year` = ?', 
                                            [$emisCode, $yearQuery['maxYear']]);
                                    $options['enrolledTotal'] = count($enrolled);
                                    $options['enrolledBoys'] = $dataConverter->countArray($enrolled, 'sex', 'M');
                                    $options['enrolledGirls'] = $options['enrolledTotal'] - $options['enrolledBoys'];

                                    //get students who exited the school
                                    $exited = $connection->fetchAll('SELECT sex, reason FROM lwd NATURAL JOIN school_exit WHERE emiscode = ? 
                                            AND `year` = ?', [$emisCode, $yearQuery['maxYear']]);
                                    //get dropouts
                                    $compString = 'completed';
                                    $dropouts = $dataConverter->selectFromArrayBool($exited, 'reason', ' != '.$compString);
                                    $options['dropoutTotal'] = count($dropouts);
                                    $options['dropoutBoys'] = $dataConverter->countArray($dropouts, 'sex', 'M');
                                    $options['dropoutGirls'] = $options['dropoutTotal'] - $options['dropoutBoys'];

                                    //transfers out
                                    $options['numBoysTRout'] = $dataConverter->countArray($learnersTransOut, 'sex', 'M');
                                    $options['numGirlsTRout'] = $dataConverter->countArray($learnersTransOut, 'sex', 'F');
                                    $options['totalTransferOut'] =  $options['numBoysTRout'] + $options['numGirlsTRout'];

                                    //transfer in
                                    $options['numBoysTRin'] = $dataConverter->countArray($learnersTransIn, 'sex', 'M');
                                    $options['numGirlsTRin'] = $dataConverter->countArray($learnersTransIn, 'sex', 'F');
                                    $options['totalTransferIn'] =  $options['numBoysTRin'] + $options['numGirlsTRin'];

                                    //get learners completed std 8
                                    $completed = $dataConverter->selectFromArrayBool($exited, 'reason', '= "completed"');
                                    $options['completedTotal'] = count($completed);
                                    $options['completedBoys'] = $dataConverter->countArray($completed, 'sex', 'M');
                                    $options['completedGirls'] = $options['completedTotal'] - $options['completedBoys'];

                                    //lwds by class, age and sex
                                    $learnersBySexAgeStd = array();
                                    $learnersBy = array();
                                    $totalStdSexAge = array();
                                    $gender = array('M'=>'M','F'=>'F');
                                    $ages = array('<6'=>5, '6'=>6, '7'=>7, 
                                        '8'=>8, '9'=>9, '10'=>10,'11'=>11,'12'=>12,
                                        '13'=>13,'14'=>14,'15'=>15,'16'=>16,'17'=>17,'>17'=>18);
                                    $stds = array('1'=>1, '2'=>2,'3'=>3,'4'=>4,'5'=>5,'6'=>6,'7'=>7,'8'=>8);
                                                        $counterStdSex = array();
                                    $counterStdBySex = array();

                                    //obtain the counter and sums for age by sex
                                    foreach ($ages as $key => $age) {
                                        $counterAgeBySex[$key]['M'] = 0;
                                        $counterAgeBySex[$key]['F'] = 0;
                                        foreach ($stds as $std) {                                                                            
                                            foreach ($gender as $sex) {
                                                if ($key == '<6'){
                                                    $learnersBy[$key][$std][$sex] = $dataConverter->countArrayMultipleBool($learners, ['age'=>$key, 'std'=>' == '.$std, 'sex'=>' == \''.$sex.'\'']);                                                   
                                                }elseif($key == '>17'){                                              
                                                    $learnersBy[$key][$std][$sex] = $dataConverter->countArrayMultipleBool($learners, ['age'=>$key, 'std'=>' == '.$std, 'sex'=>' == \''.$sex.'\'']);
                                                }else{                                                    
                                                    $learnersBy[$key][$std][$sex] = $dataConverter->countArrayMultiple($learners, ['age'=>$age, 'std'=>$std, 'sex'=>$sex]);
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
                                    $options['learnersBy'] = $learnersBy;
                                    /* end of lwds by age, sex and std*/

                                }
			}
                        /*Start of Teaching and learning materials*/
                        if(in_array(2, $formData['reports'])){ //if the Teaching and learning materials option was checked
                            $options['learningMaterials'] = true;
                            //learners needs by resource room or not - STARTS HERE
                                $learnersNeeds = $connection->fetchAll('SELECT emiscode,needname, school_has_need.* '
                                        . 'FROM school_has_need NATURAL JOIN school NATURAL JOIN need '
                                        . 'WHERE school.emiscode = ? and school_has_need.year_recorded = ?', [$emisCode, $lwdLatestYr['yr']]);
                                
                                $dbNeeds = $connection->fetchAll('SELECT idneed, needname FROM need');
                                $needs = array();
                                foreach ($dbNeeds as $key => $row) {
                                    $needs[$row['idneed']] = $row['needname'];
                                }
                                $available = array('With Learner'=> 'With Learner', 'Resource room'=>'Resource room', 'Else Where'=>'Other');
                                $teachingNeeds = array();
                                $needsCount = $dataConverter->countArray($learnersNeeds, 'emiscode', $emisCode);
                                
                                //initialise array
                                foreach ($needs as $needkey => $need) {
                                    foreach ($available as $key => $avail) {                                                                                    
                                        $teachingNeeds[$need][$avail] = 0;                                                                                   
                                    }
                                }
                                
                                //loop through the selection list summing the quantity value for each need and where it is found (resource room or not)
                                for ($x = 0; $x <= $needsCount-1; $x++){
                                    foreach ($needs as $needkey => $need) {
                                        foreach ($available as $key => $avail) {                                            
                                            if (($learnersNeeds[$x]['needname'] == $need) && ($learnersNeeds[$x]['available_in'] == $avail)){                                                                                               
                                                $teachingNeeds[$need][$avail] = $teachingNeeds[$need][$avail] + $learnersNeeds[$x]['quantity'];                                              
                                            }
                                        }
                                    }
                                }
                                //learners needs by resource room or not - ENDS HERE
                                $options['teachingNeeds'] = $teachingNeeds;
                        }
                        /*End of Teaching and learning materials*/
			
			$productionDate = new \DateTime(date('Y-m-d H:i:s'));
			$options['date'] = $productionDate;
			if($formData['format'] == 'html' || $formData['format'] == 'pdf'){
				$isHtml = ($formData['format'] == 'html')? true: false;
				$options['isHtml'] = $isHtml;
				$html = $this->renderView('school/reports/aggregate_report.html.twig', $options);
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
				$xml = $this->renderView('school/reports/aggregate_report.xml.twig', $options);
				$temporary_file_name = $this->getParameter('kernel.cache_dir').'/excel.xml'; //temporary file for storing the xml
            	file_put_contents($temporary_file_name, $xml);

            	$reader = \PHPExcel_IOFactory::createReader('Excel2003XML');
            	$excelSheet = $reader->load($temporary_file_name);
            	$writer = $this->get('phpexcel')->createWriter($excelSheet, 'Excel2007');

            	// create the response
		        $response = $this->get('phpexcel')->createStreamedResponse($writer);
		        // adding headers
		        $dispositionHeader = $response->headers->makeDisposition(
		            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
		            'stream-file.xlsx'
		        );
		        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
		        $response->headers->set('Pragma', 'public');
		        $response->headers->set('Cache-Control', 'maxage=1');
		        $response->headers->set('Content-Disposition', $dispositionHeader);

		        return $response; 
		    } 		
			
		}

		return $this->render('school/reports/reports_basic.html.twig', array('form' => $form->createView()));
	}
        
        /**
	 *@Route("/school/{emisCode}/reports/custom", name="school_custom_reports", requirements={"emisCode":"\d+"})
	 */
	public function schoolCustomReportAction($emisCode, Request $request){

		//create the form for choosing which sub-form to include and the format of the final report
		$form = $this->createForm(new CustomReportType(), null, array('use_range'=>false));			

		$form->handleRequest($request);
               
                //$formData = $form->getData();
                
		if($form->isValid()){
			$connection = $this->get('database_connection');
			$formData = $form->getData();
			$options = array(); //list of options to pass to the template
			$school = $connection->fetchAssoc('SELECT emiscode,school_name,iddistrict,district_name,idzone,zone_name 
				FROM school NATURAL JOIN district NATURAL JOIN zone WHERE emiscode = ?', [$emisCode]);
                        
			$options['school'] = $school;

			$learners = array();
			$dataConverter = $this->get('data_converter');
			if(in_array(1, $formData['reports']) || in_array(0, $formData['reports'])){

                            //get the latest year from the lwd_belongs to school table
                            $yearQuery = $connection->fetchAssoc('SELECT MAX(`year`) as maxYear FROM lwd_belongs_to_school 
                                    WHERE emiscode = ?', [$emisCode]);
                            
                            /*SN learners' details section*/
                            if(in_array(0, $formData['reports'])){//if the SN learners' details option was checked
                                    $options['snLearners'] = true;
                                    //get students enrolled this year
                                    $enrolled = $connection->fetchAll('SELECT first_name, last_name, initials, home_address, sex, dob, 
                                        distance_to_school, gfirst_name, glast_name, gsex, occupation, income_level 
                                        FROM lwd NATURAL JOIN guardian NATURAL JOIN lwd_belongs_to_school 
                                        WHERE emiscode = ? AND `year` = ?', 
                                            [$emisCode, $yearQuery['maxYear']]);				
                                    $options['snLearners'] = $enrolled;    
				}
                            if(in_array(1, $formData['reports'])){//if the SN teachers' details option was checked
                                    $options['snTeachers'] = true;
                                    //get teachers this year
                                    $employed = $connection->fetchAll('SELECT sfirst_name, employment_number,slast_name, 
                                        sinitials, s_sex, s_dob, qualification, speciality, year_started, snt_type 
                                        FROM snt NATURAL JOIN school_has_snt WHERE emiscode = ? AND `year` = ?', 
                                            [$emisCode, $yearQuery['maxYear']]);				
                                    $options['snTeachers'] = $employed;    
				}
			}			
			$productionDate = new \DateTime(date('Y-m-d H:i:s'));
			$options['date'] = $productionDate;
			if($formData['format'] == 'html' || $formData['format'] == 'pdf'){
                            $isHtml = ($formData['format'] == 'html')? true: false;
                            $options['isHtml'] = $isHtml;
                            $html = $this->renderView('school/reports/aggregate_custom.html.twig', $options);
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
                                        WHERE emiscode = ? AND `year` = ?', 
                                            [$emisCode, $yearQuery['maxYear']]);
                            //$dataConverter->excelDownload($enrolled);
                            //$dataConverter->countArrayMultipleBool($learners)
			/*	
                            $xml = $this->renderView('school/reports/aggregate_custom.xml.twig', $options);
				$temporary_file_name = $this->getParameter('kernel.cache_dir').'/excel.xml'; //temporary file for storing the xml
                                file_put_contents($temporary_file_name, $xml);

                                $reader = \PHPExcel_IOFactory::createReader('Excel2003XML');
                                $excelSheet = $reader->load($temporary_file_name);
                                $writer = $this->get('phpexcel')->createWriter($excelSheet, 'Excel2007');

                                // create the response
                                $response = $this->get('phpexcel')->createStreamedResponse($writer);
                                // adding headers
                                $dispositionHeader = $response->headers->makeDisposition(
                                    ResponseHeaderBag::DISPOSITION_ATTACHMENT,
                                    'stream-file.xlsx'
                                );
                                $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
                                $response->headers->set('Pragma', 'public');
                                $response->headers->set('Cache-Control', 'maxage=1');
                                $response->headers->set('Content-Disposition', $dispositionHeader);

                                return $response;
                         * 
                         */
                        } 		
			
		}

		return $this->render('school/reports/reports_custom.html.twig', array('form' => $form->createView()));
	}
}
?>