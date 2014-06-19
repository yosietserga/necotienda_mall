<?php
/**
 * ControllerModuleStoreTitleInstall
 * 
 * @package NecoTienda
 * @author Yosiet Serga
 * @copyright Inversiones Necoyoad, C.A.
 * @version 1.0.0
 * @access public
 * @see Controller
 */
class ControllerModuleStoreTitleInstall extends Controller {
	private $error = array(); 
	
	/**
	 * ControllerModuleStoreTitleInstall::index()
	 * 
	 * @return
	 */
	public function index() {
		if (!$this->user->hasPermission('modify', 'module/store_title/install')) {
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
			$this->modelExtension->install('module', 'store_title');
		
			$this->modelUsergroup->addPermission($this->user->getId(), 'modify', 'module/store_title/install');
			$this->modelUsergroup->addPermission($this->user->getId(), 'modify', 'module/store_title/uninstall');

			$this->modelUsergroup->addPermission($this->user->getId(), 'create', 'module/store_title/widget');
			$this->modelUsergroup->addPermission($this->user->getId(), 'access', 'module/store_title/widget');
			$this->modelUsergroup->addPermission($this->user->getId(), 'modify', 'module/store_title/widget');
			$this->modelUsergroup->addPermission($this->user->getId(), 'delete', 'module/store_title/widget');

			$this->modelUsergroup->addPermission($this->user->getId(), 'create', 'module/store_title/plugin');
			$this->modelUsergroup->addPermission($this->user->getId(), 'access', 'module/store_title/plugin');
			$this->modelUsergroup->addPermission($this->user->getId(), 'modify', 'module/store_title/plugin');
			$this->modelUsergroup->addPermission($this->user->getId(), 'delete', 'module/store_title/plugin');

			$this->redirect(Url::createAdminUrl('extension/module'));
		}
	}
	
	/**
	 * ControllerModuleStoreTitleInstall::validate()
	 * 
	 * @return
	 */
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/store_title/install')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
