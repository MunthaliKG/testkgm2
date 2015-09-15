<?php
/*this is the controller for the zone page
*it controls all links starting with zone/
*/

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ZoneController extends Controller{
    /**
     *@Route("/zone/{idzone}", name="zone_main", requirements={"idzone":"\d+"})
     */
    public function zoneMainAction($idzone, Request $request){

        $connection = $this->get('database_connection');
        $zone =  $connection->fetchAll('SELECT * FROM zone, district '
                . 'WHERE iddistrict = district_iddistrict and idzone = ?',array($idzone));
        
        //obtain the Emiscodes for the schools in this zone
        //$schools = array();
        //$schools = $connection->fetchAll('SELECT emiscode from school '
        //        . 'WHERE idzone = ?', array($idzone));
        
       
        //$emisCode = $row['emiscode'];
        $sumquery = 'SELECT count(iddisability) FROM lwd 
            NATURAL JOIN lwd_has_disability NATURAL JOIN disability NATURAL JOIN lwd_belongs_to_school NATURAL JOIN school
            WHERE idzone = ?';
        //disabilities in a zone
        $disabilities = $connection->fetchAll("SELECT disability_name, count(iddisability) as num_learners,($sumquery) as total 
            FROM lwd NATURAL JOIN lwd_has_disability NATURAL JOIN disability NATURAL JOIN lwd_belongs_to_school NATURAL JOIN school
            WHERE idzone = ? AND year = ? GROUP BY iddisability", array($idzone,$idzone,date('Y')));
        
        //schools in a zone
        $schoolsInZone = $connection->fetchAll('select emiscode, idzone from school where idzone =?', [$idzone]);
        $dataConverter = $this->get('data_converter');
        $numOfSchools = $dataConverter->countArray($schoolsInZone, 'idzone', $idzone);//get the number of schools		
        
        $session = $request->getSession();
        //keep the emiscode of the selected zone in the session so we can always redirect to it until the next school is chosen
        $session->set('idzone', $idzone);
        
        //keep the name of the selected zone in the session to access it from the school selection form
        $session->set('zone_name', $zone[0]['zone_name']);
        
        //keep zone information
        $session->set('zoneInfo', $zone[0]);

        return $this->render('zone/zone2.html.twig',
                array('zone' => $zone[0],
                        'disabilities' => $disabilities,
                    'numOfSchools' => $numOfSchools)
                );
    }
}
?>