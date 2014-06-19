<?php
/**
 * ControllerModuleSpecialInstall
 * 
 * @package NecoTienda
 * @author Yosiet Serga
 * @copyright Inversiones Necoyoad, C.A.
 * @version 1.0.0
 * @access public
 * @see Controller
 */
class ControllerModuleSpecialInstall extends Controller {
	private $error = array(); 
	
	/**
	 * ControllerModuleSpecialInstall::index()
	 * 
	 * @return
	 */
	public function index() {
		if (!$this->user->hasPermission('modify', 'module/special/install')) {
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
			$this->modelExtension->install('module', 'special');
		
			$this->modelUsergroup->addPermission($this->user->getId(), 'modify', 'module/special/install');
			$this->modelUsergroup->addPermission($this->user->getId(), 'modify', 'module/special/uninstall');

			$this->modelUsergroup->addPermission($this->user->getId(), 'create', 'module/special/widget');
			$this->modelUsergroup->addPermission($this->user->getId(), 'access', 'module/special/widget');
			$this->modelUsergroup->addPermission($this->user->getId(), 'modify', 'module/special/widget');
			$this->modelUsergroup->addPermission($this->user->getId(), 'delete', 'module/special/widget');

			$this->modelUsergroup->addPermission($this->user->getId(), 'create', 'module/special/plugin');
			$this->modelUsergroup->addPermission($this->user->getId(), 'access', 'module/special/plugin');
			$this->modelUsergroup->addPermission($this->user->getId(), 'modify', 'module/special/plugin');
			$this->modelUsergroup->addPermission($this->user->getId(), 'delete', 'module/special/plugin');

			$this->redirect(Url::createAdminUrl('extension/module'));
		}
	}
	
	/**
	 * ControllerModuleSpecialInstall::validate()
	 * 
	 * @return
	 */
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/special/install')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
