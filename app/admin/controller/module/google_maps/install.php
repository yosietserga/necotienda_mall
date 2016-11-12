<?php
/**
 * ControllerModuleGoogleMapsInstall
 * 
 * @package NecoTienda
 * @author Yosiet Serga
 * @copyright Inversiones Necoyoad, C.A.
 * @version 1.0.0
 * @access public
 * @see Controller
 */
class ControllerModuleGoogleMapsInstall extends Controller {
	private $error = array(); 
	
	/**
	 * ControllerModuleGoogleMapsInstall::index()
	 * 
	 * @return
	 */
	public function index() {
		if (!$this->user->hasPermission('modify', 'module/google_maps/install')) {
			$this->session->set('error',$this->language->get('error_permission'));
			$this->redirect(Url::createAdminUrl('extension/module'));
		} else {
		  /*
            if (file_exists('config.php')) {
                require();
            }
          */
            $this->load->auto('setting/extension');
			$this->load->auto('user/usergroup');
			$this->modelExtension->install('module', 'google_maps');
		
			$this->modelUsergroup->addPermission($this->user->getId(), 'modify', 'module/google_maps/install');
			$this->modelUsergroup->addPermission($this->user->getId(), 'modify', 'module/google_maps/uninstall');

			$this->modelUsergroup->addPermission($this->user->getId(), 'create', 'module/google_maps/widget');
			$this->modelUsergroup->addPermission($this->user->getId(), 'access', 'module/google_maps/widget');
			$this->modelUsergroup->addPermission($this->user->getId(), 'modify', 'module/google_maps/widget');
			$this->modelUsergroup->addPermission($this->user->getId(), 'delete', 'module/google_maps/widget');

			$this->modelUsergroup->addPermission($this->user->getId(), 'create', 'module/google_maps/plugin');
			$this->modelUsergroup->addPermission($this->user->getId(), 'access', 'module/google_maps/plugin');
			$this->modelUsergroup->addPermission($this->user->getId(), 'modify', 'module/google_maps/plugin');
			$this->modelUsergroup->addPermission($this->user->getId(), 'delete', 'module/google_maps/plugin');

			$this->redirect(Url::createAdminUrl('extension/module'));
		}
	}
	
	/**
	 * ControllerModuleGoogleMapsInstall::validate()
	 * 
	 * @return
	 */
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/google_maps/install')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
