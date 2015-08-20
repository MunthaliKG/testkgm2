<?php
/*this is the controller for producing reports for the school section*/
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class SchoolReportController extends Controller{
	/**
	 *@Route("/school/{emisCode}/reports", name="school_reports", requirements={"emisCode":"\d+"})
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

			/*Preliminary counts section*/
			$learners = array();
			$dataConverter = $this->get('data_converter');
			if(in_array(0, $formData['reports'])){ //if the preliminary counts option was checked
				$options['preliminary'] = true;

				//learner preliminary counts
				$learners = $connection->fetchAll('SELECT * FROM lwd_has_disability NATURAL JOIN lwd NATURAL JOIN lwd_belongs_to_school 
					WHERE emiscode = ?', [$emisCode]);
				$options['numBoys'] = $dataConverter->countArray($learners, 'sex', 'M');//get the number of boys
				$options['numGirls'] = $dataConverter->countArray($learners, 'sex', 'M');//get the number of girls

				//snt preliminary counts
				$latestYr = $connection->fetchAssoc('SELECT MAX(year) AS yr FROM school_has_snt 
					WHERE emiscode = ?',[$emisCode]);
				$teachers = $connection->fetchAll('SELECT * FROM school_has_snt NATURAL JOIN snt 
					WHERE `year` = ? AND emiscode = ?', [$latestYr['yr'], $emisCode]);
				$options['sntMale'] = $dataConverter->countArray($teachers, 's_sex', 'M');
				$options['sntFemale'] = $dataConverter->countArray($teachers, 's_sex', 'F');
			}
			/*End of preliminary counts section*/

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
}
?>