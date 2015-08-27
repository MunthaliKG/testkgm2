<?php 
namespace AppBundle\Helpers;

use AppBundle\Form\Type\SchoolFinderType;
use AppBundle\Form\Type\EmisSchoolFinderType;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/*This class creates the two form for selecting a school either by typing in an EMIS code
or by selecting a district and then a school
*/
class FindZoneForm{
	protected $form1;
	//protected $form2;
	protected $formData;
	protected $em;
	protected $formFactory;
	protected $requestStack;
	protected $isValid = false;
	protected $error = null;
	protected $zoneId;
        protected $districtId;

	function __construct(EntityManager $em, FormFactoryInterface $ffi, RequestStack $rs){
		$this->em = $em;
		$this->formFactory = $ffi;
		$this->requestStack = $rs;
	}
	function createForms($formAction){
		$defaultData = array('field' => 'Please enter your name');/*passing this into the createForm() method causes the form values
        to be put into an array as opposed to an object*/

        $fBag = $this->requestStack->
				getCurrentRequest()->
				getSession()->
				getFlashBag();
        /*create the array to be used for storing the list of schools for the dynamically populated select element. 
        (set to an empty array if not found in the session)  */
        $zoneList = array();
        if($fBag->has('zoneList')){
            $zoneList = $fBag->get('zoneList');
            foreach($zoneList as $zone){
                $this->em->persist($zone);
            }
        }

        $this->form1 = $this->formFactory->create(new \AppBundle\Form\Type\ZoneFinderType($zoneList), $defaultData, array(
        				'action' => $formAction));/*create a form using the
        SchoolFinderType form class. This form enables to choose a school by selecting a district and a school*/
       // $this->form1 = $this->formFactory->create(new EmisSchoolFinderType(), $defaultData, array(
        //				'action' => $formAction));/*create a form using the 
       // EmisSchoolFinderType form class (enables choosing a school by typing in an Emis Code)*/
	}
	function getForm1(){
		return $this->form1;
	}
//	function getForm2(){
//		return $this->form2;
//	}
	function getView1(){
		return $this->form1->createView();
	}
//	function getView2(){
//		return $this->form2->createView();
//	}
	function processForms(){
		$this->form1->handleRequest($this->requestStack->getCurrentRequest());
//		$this->form2->handleRequest($this->requestStack->getCurrentRequest());
		$zone;
        if($this->form1->isValid()){/*also returns false if form2 was not submitted or contains invalid values*/
			$this->formData = $this->form1->getData();           
            $zone = $this->em->getRepository('AppBundle:Zone')->find($this->formData['zone']);
            if($zone){
                $this->isValid = true;

                $this->zoneId = $zone->getIdzone();
                $this->districtId = $this->formData['district'];

                //$this->schoolId = $school->getId();

            }else{
                $this->error = "That zone does not exist";
            }  
         }
	}
	function areValid(){
		return $this->isValid;
	}
	function getError(){
		return $this->error;
	}
	function getZoneId(){
		return $this->zoneId;
	}
        function getDistrictId(){
		return $this->districtId;
	}
}

 ?>