<?php
/**
 * ControllerModuleSocialInstall
 * 
 * @package NecoTienda
 * @author Yosiet Serga
 * @copyright Inversiones Necoyoad, C.A.
 * @version 1.0.0
 * @access public
 * @see Controller
 */
class ControllerModuleSocialInstall extends Controller {
	private $error = array(); 
	
	/**
	 * ControllerModuleSocialInstall::index()
	 * 
	 * @return
	 */
	public function index() {
		if (!$this->user->hasPermission('modify', 'module/social/install')) {
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
			$this->modelExtension->install('module', 'social');
		
			$this->modelUsergroup->addPermission($this->user->getId(), 'modify', 'module/social/install');
			$this->modelUsergroup->addPermission($this->user->getId(), 'modify', 'module/social/uninstall');

			$this->modelUsergroup->addPermission($this->user->getId(), 'create', 'module/social/widget');
			$this->modelUsergroup->addPermission($this->user->getId(), 'access', 'module/social/widget');
			$this->modelUsergroup->addPermission($this->user->getId(), 'modify', 'module/social/widget');
			$this->modelUsergroup->addPermission($this->user->getId(), 'delete', 'module/social/widget');

			$this->modelUsergroup->addPermission($this->user->getId(), 'create', 'module/social/plugin');
			$this->modelUsergroup->addPermission($this->user->getId(), 'access', 'module/social/plugin');
			$this->modelUsergroup->addPermission($this->user->getId(), 'modify', 'module/social/plugin');
			$this->modelUsergroup->addPermission($this->user->getId(), 'delete', 'module/social/plugin');

			$this->redirect(Url::createAdminUrl('extension/module'));
		}
	}
	
	/**
	 * ControllerModuleSocialInstall::validate()
	 * 
	 * @return
	 */
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/social/install')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
