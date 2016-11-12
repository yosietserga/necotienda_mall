<?php
/**
 * ControllerModulePlainTextInstall
 * 
 * @package NecoTienda
 * @author Yosiet Serga
 * @copyright Inversiones Necoyoad, C.A.
 * @version 1.0.0
 * @access public
 * @see Controller
 */
class ControllerModulePlainTextInstall extends Controller {
	private $error = array(); 
	
	/**
	 * ControllerModulePlainTextInstall::index()
	 * 
	 * @return
	 */
	public function index() {
		if (!$this->user->hasPermission('modify', 'module/plaintext/install')) {
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
			$this->modelExtension->install('module', 'plaintext');
		
			$this->modelUsergroup->addPermission($this->user->getId(), 'access', 'module/plaintext/install');
			$this->modelUsergroup->addPermission($this->user->getId(), 'modify', 'module/plaintext/install');
            
			$this->modelUsergroup->addPermission($this->user->getId(), 'access', 'module/plaintext/uninstall');
			$this->modelUsergroup->addPermission($this->user->getId(), 'modify', 'module/plaintext/uninstall');

			$this->modelUsergroup->addPermission($this->user->getId(), 'access', 'module/plaintext/widget');
			$this->modelUsergroup->addPermission($this->user->getId(), 'modify', 'module/plaintext/widget');
			$this->modelUsergroup->addPermission($this->user->getId(), 'delete', 'module/plaintext/widget');

			$this->redirect(Url::createAdminUrl('extension/module'));
		}
	}
	
	/**
	 * ControllerModulePlainTextInstall::validate()
	 * 
	 * @return
	 */
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/plaintext/install')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
