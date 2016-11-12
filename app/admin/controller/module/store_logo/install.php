<?php
/**
 * ControllerModuleStoreLogoInstall
 * 
 * @package NecoTienda
 * @author Yosiet Serga
 * @copyright Inversiones Necoyoad, C.A.
 * @version 1.0.0
 * @access public
 * @see Controller
 */
class ControllerModuleStoreLogoInstall extends Controller {
	private $error = array(); 
	
	/**
	 * ControllerModuleStoreLogoInstall::index()
	 * 
	 * @return
	 */
	public function index() {
		if (!$this->user->hasPermission('modify', 'module/store_logo/install')) {
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
			$this->modelExtension->install('module', 'store_logo');
		
			$this->modelUsergroup->addPermission($this->user->getId(), 'modify', 'module/store_logo/install');
			$this->modelUsergroup->addPermission($this->user->getId(), 'modify', 'module/store_logo/uninstall');

			$this->modelUsergroup->addPermission($this->user->getId(), 'create', 'module/store_logo/widget');
			$this->modelUsergroup->addPermission($this->user->getId(), 'access', 'module/store_logo/widget');
			$this->modelUsergroup->addPermission($this->user->getId(), 'modify', 'module/store_logo/widget');
			$this->modelUsergroup->addPermission($this->user->getId(), 'delete', 'module/store_logo/widget');

			$this->modelUsergroup->addPermission($this->user->getId(), 'create', 'module/store_logo/plugin');
			$this->modelUsergroup->addPermission($this->user->getId(), 'access', 'module/store_logo/plugin');
			$this->modelUsergroup->addPermission($this->user->getId(), 'modify', 'module/store_logo/plugin');
			$this->modelUsergroup->addPermission($this->user->getId(), 'delete', 'module/store_logo/plugin');

			$this->redirect(Url::createAdminUrl('extension/module'));
		}
	}
	
	/**
	 * ControllerModuleStoreLogoInstall::validate()
	 * 
	 * @return
	 */
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/store_logo/install')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
