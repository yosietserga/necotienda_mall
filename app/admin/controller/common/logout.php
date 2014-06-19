<?php       
class ControllerCommonLogout extends Controller {   
	public function index() { 
    	$this->user->logout();
 
 		$this->session->clear('token');

		$this->redirect(Url::createAdminUrl('common/login'));
  	}
}  
