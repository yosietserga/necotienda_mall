<?php
/**
 * ControllerModuleShareInstall
 * 
 * @package NecoTienda
 * @author Yosiet Serga
 * @copyright Inversiones Necoyoad, C.A.
 * @version 1.0.0
 * @access public
 * @see Controller
 */
class ControllerModuleShareInstall extends Controller {
	private $error = array(); 
	
	/**
	 * ControllerModuleShareInstall::index()
	 * 
	 * @return
	 */
	public function index() {
		if (!$this->user->hasPermission('modify', 'module/share/install')) {
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
			$this->modelExtension->install('module', 'share');
		
			$this->modelUsergroup->addPermission($this->user->getId(), 'modify', 'module/share/install');
			$this->modelUsergroup->addPermission($this->user->getId(), 'modify', 'module/share/uninstall');

			$this->modelUsergroup->addPermission($this->user->getId(), 'create', 'module/share/widget');
			$this->modelUsergroup->addPermission($this->user->getId(), 'access', 'module/share/widget');
			$this->modelUsergroup->addPermission($this->user->getId(), 'modify', 'module/share/widget');
			$this->modelUsergroup->addPermission($this->user->getId(), 'delete', 'module/share/widget');

			$this->modelUsergroup->addPermission($this->user->getId(), 'create', 'module/share/plugin');
			$this->modelUsergroup->addPermission($this->user->getId(), 'access', 'module/share/plugin');
			$this->modelUsergroup->addPermission($this->user->getId(), 'modify', 'module/share/plugin');
			$this->modelUsergroup->addPermission($this->user->getId(), 'delete', 'module/share/plugin');

			$this->redirect(Url::createAdminUrl('extension/module'));
		}
	}
	
	/**
	 * ControllerModuleShareInstall::validate()
	 * 
	 * @return
	 */
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/share/install')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
