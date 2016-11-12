<?php
/**
 * ControllerModuleCategoryInstall
 * 
 * @package NecoTienda
 * @author Yosiet Serga
 * @copyright Inversiones Necoyoad, C.A.
 * @version 1.0.0
 * @access public
 * @see Controller
 */
class ControllerModuleCategoryInstall extends Controller {
	private $error = array(); 
	
	/**
	 * ControllerModuleCategoryInstall::index()
	 * 
	 * @return
	 */
	public function index() {
		if (!$this->user->hasPermission('modify', 'module/category/install')) {
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
			$this->modelExtension->install('module', 'category');
		
			$this->modelUsergroup->addPermission($this->user->getId(), 'modify', 'module/category/install');
			$this->modelUsergroup->addPermission($this->user->getId(), 'modify', 'module/category/uninstall');

			$this->modelUsergroup->addPermission($this->user->getId(), 'create', 'module/category/widget');
			$this->modelUsergroup->addPermission($this->user->getId(), 'access', 'module/category/widget');
			$this->modelUsergroup->addPermission($this->user->getId(), 'modify', 'module/category/widget');
			$this->modelUsergroup->addPermission($this->user->getId(), 'delete', 'module/category/widget');

			$this->modelUsergroup->addPermission($this->user->getId(), 'create', 'module/category/plugin');
			$this->modelUsergroup->addPermission($this->user->getId(), 'access', 'module/category/plugin');
			$this->modelUsergroup->addPermission($this->user->getId(), 'modify', 'module/category/plugin');
			$this->modelUsergroup->addPermission($this->user->getId(), 'delete', 'module/category/plugin');

			$this->redirect(Url::createAdminUrl('extension/module'));
		}
	}
	
	/**
	 * ControllerModuleCategoryInstall::validate()
	 * 
	 * @return
	 */
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/category/install')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
