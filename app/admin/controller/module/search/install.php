<?php
/**
 * ControllerModuleSearchInstall
 * 
 * @package NecoTienda
 * @author Yosiet Serga
 * @copyright Inversiones Necoyoad, C.A.
 * @version 1.0.0
 * @access public
 * @see Controller
 */
class ControllerModuleSearchInstall extends Controller {
	private $error = array(); 
	
	/**
	 * ControllerModuleSearchInstall::index()
	 * 
	 * @return
	 */
	public function index() {
		if (!$this->user->hasPermission('modify', 'module/search/install')) {
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
			$this->modelExtension->install('module', 'search');
		
			$this->modelUsergroup->addPermission($this->user->getId(), 'access', 'module/search/install');
			$this->modelUsergroup->addPermission($this->user->getId(), 'modify', 'module/search/install');
            
			$this->modelUsergroup->addPermission($this->user->getId(), 'access', 'module/search/uninstall');
			$this->modelUsergroup->addPermission($this->user->getId(), 'modify', 'module/search/uninstall');

			$this->modelUsergroup->addPermission($this->user->getId(), 'access', 'module/search/widget');
			$this->modelUsergroup->addPermission($this->user->getId(), 'modify', 'module/search/widget');
			$this->modelUsergroup->addPermission($this->user->getId(), 'delete', 'module/search/widget');

			$this->redirect(Url::createAdminUrl('extension/module'));
		}
	}
	
	/**
	 * ControllerModuleSearchInstall::validate()
	 * 
	 * @return
	 */
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/search/install')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
