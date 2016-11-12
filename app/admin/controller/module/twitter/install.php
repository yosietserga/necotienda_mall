<?php
/**
 * ControllerModuleTwitterInstall
 * 
 * @package NecoTienda
 * @author Yosiet Serga
 * @copyright Inversiones Necoyoad, C.A.
 * @version 1.0.0
 * @access public
 * @see Controller
 */
class ControllerModuleTwitterInstall extends Controller {
	private $error = array(); 
	
	/**
	 * ControllerModuleTwitterInstall::index()
	 * 
	 * @return
	 */
	public function index() {
		if (!$this->user->hasPermission('modify', 'module/twitter/install')) {
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
			$this->modelExtension->install('module', 'twitter');
		
			$this->modelUsergroup->addPermission($this->user->getId(), 'modify', 'module/twitter/install');
			$this->modelUsergroup->addPermission($this->user->getId(), 'modify', 'module/twitter/uninstall');

			$this->modelUsergroup->addPermission($this->user->getId(), 'create', 'module/twitter/widget');
			$this->modelUsergroup->addPermission($this->user->getId(), 'access', 'module/twitter/widget');
			$this->modelUsergroup->addPermission($this->user->getId(), 'modify', 'module/twitter/widget');
			$this->modelUsergroup->addPermission($this->user->getId(), 'delete', 'module/twitter/widget');

			$this->modelUsergroup->addPermission($this->user->getId(), 'create', 'module/twitter/plugin');
			$this->modelUsergroup->addPermission($this->user->getId(), 'access', 'module/twitter/plugin');
			$this->modelUsergroup->addPermission($this->user->getId(), 'modify', 'module/twitter/plugin');
			$this->modelUsergroup->addPermission($this->user->getId(), 'delete', 'module/twitter/plugin');

			$this->redirect(Url::createAdminUrl('extension/module'));
		}
	}
	
	/**
	 * ControllerModuleTwitterInstall::validate()
	 * 
	 * @return
	 */
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/twitter/install')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
