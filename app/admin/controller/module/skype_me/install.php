<?php
/**
 * ControllerModuleSkypeMeInstall
 * 
 * @package NecoTienda
 * @author Yosiet Serga
 * @copyright Inversiones Necoyoad, C.A.
 * @version 1.0.0
 * @access public
 * @see Controller
 */
class ControllerModuleSkypeMeInstall extends Controller {
	private $error = array(); 
	
	/**
	 * ControllerModuleSkypeMeInstall::index()
	 * 
	 * @return
	 */
	public function index() {
		if (!$this->user->hasPermission('modify', 'module/skype_me/install')) {
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
			$this->modelExtension->install('module', 'skype_me');
		
			$this->modelUsergroup->addPermission($this->user->getId(), 'modify', 'module/skype_me/install');
			$this->modelUsergroup->addPermission($this->user->getId(), 'modify', 'module/skype_me/uninstall');

			$this->modelUsergroup->addPermission($this->user->getId(), 'create', 'module/skype_me/widget');
			$this->modelUsergroup->addPermission($this->user->getId(), 'access', 'module/skype_me/widget');
			$this->modelUsergroup->addPermission($this->user->getId(), 'modify', 'module/skype_me/widget');
			$this->modelUsergroup->addPermission($this->user->getId(), 'delete', 'module/skype_me/widget');

			$this->modelUsergroup->addPermission($this->user->getId(), 'create', 'module/skype_me/plugin');
			$this->modelUsergroup->addPermission($this->user->getId(), 'access', 'module/skype_me/plugin');
			$this->modelUsergroup->addPermission($this->user->getId(), 'modify', 'module/skype_me/plugin');
			$this->modelUsergroup->addPermission($this->user->getId(), 'delete', 'module/skype_me/plugin');

			$this->redirect(Url::createAdminUrl('extension/module'));
		}
	}
	
	/**
	 * ControllerModuleSkypeMeInstall::validate()
	 * 
	 * @return
	 */
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/skype_me/install')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
