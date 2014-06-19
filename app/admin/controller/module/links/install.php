<?php
/**
 * ControllerModuleLinksInstall
 * 
 * @package NecoTienda
 * @author Yosiet Serga
 * @copyright Inversiones Necoyoad, C.A.
 * @version 1.0.0
 * @access public
 * @see Controller
 */
class ControllerModuleLinksInstall extends Controller {
	private $error = array(); 
	
	/**
	 * ControllerModuleLinksInstall::index()
	 * 
	 * @return
	 */
	public function index() {
		if (!$this->user->hasPermission('modify', 'module/links/install')) {
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
			$this->modelExtension->install('module', 'links');
		
			$this->modelUsergroup->addPermission($this->user->getId(), 'modify', 'module/links/install');
			$this->modelUsergroup->addPermission($this->user->getId(), 'modify', 'module/links/uninstall');

			$this->modelUsergroup->addPermission($this->user->getId(), 'create', 'module/links/widget');
			$this->modelUsergroup->addPermission($this->user->getId(), 'access', 'module/links/widget');
			$this->modelUsergroup->addPermission($this->user->getId(), 'modify', 'module/links/widget');
			$this->modelUsergroup->addPermission($this->user->getId(), 'delete', 'module/links/widget');

			$this->modelUsergroup->addPermission($this->user->getId(), 'create', 'module/links/plugin');
			$this->modelUsergroup->addPermission($this->user->getId(), 'access', 'module/links/plugin');
			$this->modelUsergroup->addPermission($this->user->getId(), 'modify', 'module/links/plugin');
			$this->modelUsergroup->addPermission($this->user->getId(), 'delete', 'module/links/plugin');

			$this->redirect(Url::createAdminUrl('extension/module'));
		}
	}
	
	/**
	 * ControllerModuleLinksInstall::validate()
	 * 
	 * @return
	 */
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/links/install')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
