<?php
/**
 * ControllerModulePageInstall
 * 
 * @package NecoTienda
 * @author Yosiet Serga
 * @copyright Inversiones Necoyoad, C.A.
 * @version 1.0.0
 * @access public
 * @see Controller
 */
class ControllerModulePageInstall extends Controller {
	private $error = array(); 
	
	/**
	 * ControllerModulePageInstall::index()
	 * 
	 * @return
	 */
	public function index() {
		if (!$this->user->hasPermission('modify', 'module/page/install')) {
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
			$this->modelExtension->install('module', 'page');
		
			$this->modelUsergroup->addPermission($this->user->getId(), 'modify', 'module/page/install');
			$this->modelUsergroup->addPermission($this->user->getId(), 'modify', 'module/page/uninstall');

			$this->modelUsergroup->addPermission($this->user->getId(), 'create', 'module/page/widget');
			$this->modelUsergroup->addPermission($this->user->getId(), 'access', 'module/page/widget');
			$this->modelUsergroup->addPermission($this->user->getId(), 'modify', 'module/page/widget');
			$this->modelUsergroup->addPermission($this->user->getId(), 'delete', 'module/page/widget');

			$this->modelUsergroup->addPermission($this->user->getId(), 'create', 'module/page/plugin');
			$this->modelUsergroup->addPermission($this->user->getId(), 'access', 'module/page/plugin');
			$this->modelUsergroup->addPermission($this->user->getId(), 'modify', 'module/page/plugin');
			$this->modelUsergroup->addPermission($this->user->getId(), 'delete', 'module/page/plugin');

			$this->redirect(Url::createAdminUrl('extension/module'));
		}
	}
	
	/**
	 * ControllerModulePageInstall::validate()
	 * 
	 * @return
	 */
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/page/install')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
