<?php
/**
 * ControllerModuleLoginInstall
 * 
 * @package NecoTienda
 * @author Yosiet Serga
 * @copyright Inversiones Necoyoad, C.A.
 * @version 1.0.0
 * @access public
 * @see Controller
 */
class ControllerModuleLoginInstall extends Controller {
	private $error = array(); 
	
	/**
	 * ControllerModuleLoginInstall::index()
	 * 
	 * @return
	 */
	public function index() {
		if (!$this->user->hasPermission('modify', 'module/login/install')) {
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
			$this->modelExtension->install('module', 'login');
		
			$this->modelUsergroup->addPermission($this->user->getId(), 'modify', 'module/login/install');
			$this->modelUsergroup->addPermission($this->user->getId(), 'modify', 'module/login/uninstall');

			$this->modelUsergroup->addPermission($this->user->getId(), 'create', 'module/login/widget');
			$this->modelUsergroup->addPermission($this->user->getId(), 'access', 'module/login/widget');
			$this->modelUsergroup->addPermission($this->user->getId(), 'modify', 'module/login/widget');
			$this->modelUsergroup->addPermission($this->user->getId(), 'delete', 'module/login/widget');

			$this->modelUsergroup->addPermission($this->user->getId(), 'create', 'module/login/plugin');
			$this->modelUsergroup->addPermission($this->user->getId(), 'access', 'module/login/plugin');
			$this->modelUsergroup->addPermission($this->user->getId(), 'modify', 'module/login/plugin');
			$this->modelUsergroup->addPermission($this->user->getId(), 'delete', 'module/login/plugin');

			$this->redirect(Url::createAdminUrl('extension/module'));
		}
	}
	
	/**
	 * ControllerModuleLoginInstall::validate()
	 * 
	 * @return
	 */
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/login/install')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
