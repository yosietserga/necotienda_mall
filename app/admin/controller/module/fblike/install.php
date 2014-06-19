<?php
/**
 * ControllerModuleFBLikeInstall
 * 
 * @package NecoTienda
 * @author Yosiet Serga
 * @copyright Inversiones Necoyoad, C.A.
 * @version 1.0.0
 * @access public
 * @see Controller
 */
class ControllerModuleFBLikeInstall extends Controller {
	private $error = array(); 
	
	/**
	 * ControllerModuleFBLikeInstall::index()
	 * 
	 * @return
	 */
	public function index() {
		if (!$this->user->hasPermission('modify', 'module/fblike/install')) {
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
			$this->modelExtension->install('module', 'fblike');
		
			$this->modelUsergroup->addPermission($this->user->getId(), 'modify', 'module/fblike/install');
			$this->modelUsergroup->addPermission($this->user->getId(), 'modify', 'module/fblike/uninstall');

			$this->modelUsergroup->addPermission($this->user->getId(), 'create', 'module/fblike/widget');
			$this->modelUsergroup->addPermission($this->user->getId(), 'access', 'module/fblike/widget');
			$this->modelUsergroup->addPermission($this->user->getId(), 'modify', 'module/fblike/widget');
			$this->modelUsergroup->addPermission($this->user->getId(), 'delete', 'module/fblike/widget');

			$this->modelUsergroup->addPermission($this->user->getId(), 'create', 'module/fblike/plugin');
			$this->modelUsergroup->addPermission($this->user->getId(), 'access', 'module/fblike/plugin');
			$this->modelUsergroup->addPermission($this->user->getId(), 'modify', 'module/fblike/plugin');
			$this->modelUsergroup->addPermission($this->user->getId(), 'delete', 'module/fblike/plugin');

			$this->redirect(Url::createAdminUrl('extension/module'));
		}
	}
	
	/**
	 * ControllerModuleFBLikeInstall::validate()
	 * 
	 * @return
	 */
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/fblike/install')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
