<?php
/**
 * ControllerModuleLatestInstall
 * 
 * @package NecoTienda
 * @author Yosiet Serga
 * @copyright Inversiones Necoyoad, C.A.
 * @version 1.0.0
 * @access public
 * @see Controller
 */
class ControllerModuleLatestInstall extends Controller {
	private $error = array(); 
	
	/**
	 * ControllerModuleLatestInstall::index()
	 * 
	 * @return
	 */
	public function index() {
		if (!$this->user->hasPermission('modify', 'module/latest/install')) {
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
			$this->modelExtension->install('module', 'latest');
		
			$this->modelUsergroup->addPermission($this->user->getId(), 'modify', 'module/latest/install');
			$this->modelUsergroup->addPermission($this->user->getId(), 'modify', 'module/latest/uninstall');

			$this->modelUsergroup->addPermission($this->user->getId(), 'create', 'module/latest/widget');
			$this->modelUsergroup->addPermission($this->user->getId(), 'access', 'module/latest/widget');
			$this->modelUsergroup->addPermission($this->user->getId(), 'modify', 'module/latest/widget');
			$this->modelUsergroup->addPermission($this->user->getId(), 'delete', 'module/latest/widget');

			$this->modelUsergroup->addPermission($this->user->getId(), 'create', 'module/latest/plugin');
			$this->modelUsergroup->addPermission($this->user->getId(), 'access', 'module/latest/plugin');
			$this->modelUsergroup->addPermission($this->user->getId(), 'modify', 'module/latest/plugin');
			$this->modelUsergroup->addPermission($this->user->getId(), 'delete', 'module/latest/plugin');

			$this->redirect(Url::createAdminUrl('extension/module'));
		}
	}
	
	/**
	 * ControllerModuleLatestInstall::validate()
	 * 
	 * @return
	 */
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/latest/install')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
