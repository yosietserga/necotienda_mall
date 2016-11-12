<?php
/**
 * ControllerModuleSubscribeInstall
 * 
 * @package NecoTienda
 * @author Yosiet Serga
 * @copyright Inversiones Necoyoad, C.A.
 * @version 1.0.0
 * @access public
 * @see Controller
 */
class ControllerModuleSubscribeInstall extends Controller {
	private $error = array(); 
	
	/**
	 * ControllerModuleSubscribeInstall::index()
	 * 
	 * @return
	 */
	public function index() {
		if (!$this->user->hasPermission('modify', 'module/subscribe/install')) {
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
			$this->modelExtension->install('module', 'subscribe');
		
			$this->modelUsergroup->addPermission($this->user->getId(), 'modify', 'module/subscribe/install');
			$this->modelUsergroup->addPermission($this->user->getId(), 'modify', 'module/subscribe/uninstall');

			$this->modelUsergroup->addPermission($this->user->getId(), 'create', 'module/subscribe/widget');
			$this->modelUsergroup->addPermission($this->user->getId(), 'access', 'module/subscribe/widget');
			$this->modelUsergroup->addPermission($this->user->getId(), 'modify', 'module/subscribe/widget');
			$this->modelUsergroup->addPermission($this->user->getId(), 'delete', 'module/subscribe/widget');

			$this->modelUsergroup->addPermission($this->user->getId(), 'create', 'module/subscribe/plugin');
			$this->modelUsergroup->addPermission($this->user->getId(), 'access', 'module/subscribe/plugin');
			$this->modelUsergroup->addPermission($this->user->getId(), 'modify', 'module/subscribe/plugin');
			$this->modelUsergroup->addPermission($this->user->getId(), 'delete', 'module/subscribe/plugin');

			$this->redirect(Url::createAdminUrl('extension/module'));
		}
	}
	
	/**
	 * ControllerModuleSubscribeInstall::validate()
	 * 
	 * @return
	 */
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/subscribe/install')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
