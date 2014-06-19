<?php
/**
 * ControllerModuleManufacturerInstall
 * 
 * @package NecoTienda
 * @author Yosiet Serga
 * @copyright Inversiones Necoyoad, C.A.
 * @version 1.0.0
 * @access public
 * @see Controller
 */
class ControllerModuleManufacturerInstall extends Controller {
	private $error = array(); 
	
	/**
	 * ControllerModuleManufacturerInstall::index()
	 * 
	 * @return
	 */
	public function index() {
		if (!$this->user->hasPermission('modify', 'module/manufacturer/install')) {
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
			$this->modelExtension->install('module', 'manufacturer');
		
			$this->modelUsergroup->addPermission($this->user->getId(), 'modify', 'module/manufacturer/install');
			$this->modelUsergroup->addPermission($this->user->getId(), 'modify', 'module/manufacturer/uninstall');

			$this->modelUsergroup->addPermission($this->user->getId(), 'create', 'module/manufacturer/widget');
			$this->modelUsergroup->addPermission($this->user->getId(), 'access', 'module/manufacturer/widget');
			$this->modelUsergroup->addPermission($this->user->getId(), 'modify', 'module/manufacturer/widget');
			$this->modelUsergroup->addPermission($this->user->getId(), 'delete', 'module/manufacturer/widget');

			$this->modelUsergroup->addPermission($this->user->getId(), 'create', 'module/manufacturer/plugin');
			$this->modelUsergroup->addPermission($this->user->getId(), 'access', 'module/manufacturer/plugin');
			$this->modelUsergroup->addPermission($this->user->getId(), 'modify', 'module/manufacturer/plugin');
			$this->modelUsergroup->addPermission($this->user->getId(), 'delete', 'module/manufacturer/plugin');

			$this->redirect(Url::createAdminUrl('extension/module'));
		}
	}
	
	/**
	 * ControllerModuleManufacturerInstall::validate()
	 * 
	 * @return
	 */
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/manufacturer/install')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
