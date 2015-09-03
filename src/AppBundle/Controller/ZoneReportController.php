<?php
/*this is the controller for producing reports for the school section*/
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class ZoneReportController extends Controller{
	/**
	 *@Route("/zone/{idzone}/reports", name="zone_snl", requirements={"idzone":"\d+"})
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
                        //echo $idzone;
                        //exit;
                        $options['zone'] = $zone[0];
                        //$options['zoneName'] = $zone[0]['zone_name'];
                        //$options['districtId'] = $zone['iddistrcit'];
                        //$options['districtName'] = $zone['district_name'];
			//$school = $connection->fetchAssoc('SELECT emiscode,school_name,iddistrict,district_name,idzone,zone_name 
			//	FROM school NATURAL JOIN district NATURAL JOIN zone WHERE emiscode = ?', [$emisCode]);

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
                               
                                
                                //$options['learnersBy'] = $learnersBy;
                                //$options['teachingNeeds'] = $teachingNeeds;
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
                                $options['rmAdaptiveChairs'] = $dataConverter->countArrayBool($rooms, 'adaptive_chairs', '>0');
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
                                    .'from lwd NATURAL JOIN lwd_belongs_to_school NATURAL JOIN performance NATURAL JOIN school NATURAL JOIN zone '
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
                        if(in_array(2, $formData['reports'])){ //if the Teaching and learning materials option was checked
                            $options['learningMaterials'] = true;
                            //learners needs by resource room or not - STARTS HERE
                                $learnersNeeds = $connection->fetchAll('SELECT idzone,needname, school_has_need.* '
                                        . 'FROM school_has_need NATURAL JOIN school NATURAL JOIN zone NATURAL JOIN need '
                                        . 'WHERE idzone = ? and school_has_need.year_recorded = ?', [$idzone, $lwdLatestYr['yr']]);
                                
                                $dbNeeds = $connection->fetchAll('SELECT idneed, needname FROM need');
                                $needs = array();
                                foreach ($dbNeeds as $key => $row) {
                                    $needs[$row['idneed']] = $row['needname'];
                                }
                                $available = array('Yes'=>'Yes','No'=>'No');
                                $teachingNeeds = array();
                                $needsCount = $dataConverter->countArray($learnersNeeds, 'idzone', $idzone);
                                
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
                        //exit;
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
			}else{
                            $xml = $this->renderView('zone/reports/aggregate_zone_report.xml.twig', $options);
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

		return $this->render('zone/reports/zone_reports_basic.html.twig', array('form' => $form->createView()));
	}
}
?>

