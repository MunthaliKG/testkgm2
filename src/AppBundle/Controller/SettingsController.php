<?php
/*this is the controller for the admin settings page
*it controls all links starting with admin/settings/
*/

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SettingsController extends Controller{
	/**
	 *@Route("/admin/settings/disabilities", name="admin_settings_disabilities")
	 */
	public function adminSettingsDisabilitiesAction(Request $request){
		return $this->render('admin/settings/admin_disabilities.html.twig');
	}
}


?>