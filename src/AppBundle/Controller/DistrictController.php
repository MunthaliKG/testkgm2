<?php
/*this is the controller for the zone page
*it controls all links starting with zone/
*/

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DistrictController extends Controller{
    /**
     *@Route("/district/{iddistrict}", name="district_main", requirements={"iddistrict":"\d+"})
     */
    public function districtMainAction($iddistrict, Request $request){

        $connection = $this->get('database_connection');
        $district =  $connection->fetchAll('SELECT * FROM district '
                . 'WHERE iddistrict = ?',array($iddistrict));
          
        $sumquery = 'SELECT count(iddisability) FROM lwd 
            NATURAL JOIN lwd_has_disability NATURAL JOIN disability NATURAL JOIN lwd_belongs_to_school NATURAL JOIN school
            WHERE iddistrict = ?';
        //disabilities in a district
        $disabilities = $connection->fetchAll("SELECT disability_name, count(iddisability) as num_learners,($sumquery) as total 
            FROM lwd NATURAL JOIN lwd_has_disability NATURAL JOIN disability NATURAL JOIN lwd_belongs_to_school NATURAL JOIN school
            WHERE iddistrict = ? AND year = ? GROUP BY iddisability", array($iddistrict,$iddistrict,date('Y')));
        
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
                    'numOfSchools' => $numOfSchools)
                );
    }
}
?>