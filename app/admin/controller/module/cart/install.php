<?php
/**
 * ControllerModuleCartInstall
 * 
 * @package NecoTienda
 * @author Yosiet Serga
 * @copyright Inversiones Necoyoad, C.A.
 * @version 1.0.0
 * @access public
 * @see Controller
 */
class ControllerModuleCartInstall extends Controller {
	private $error = array(); 
	
	/**
	 * ControllerModuleCartInstall::index()
	 * 
	 * @return
	 */
	public function index() {
		if (!$this->user->hasPermission('modify', 'module/cart/install')) {
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
			$this->modelExtension->install('module', 'cart');
		
			$this->modelUsergroup->addPermission($this->user->getId(), 'modify', 'module/cart/install');
			$this->modelUsergroup->addPermission($this->user->getId(), 'modify', 'module/cart/uninstall');

			$this->modelUsergroup->addPermission($this->user->getId(), 'create', 'module/cart/widget');
			$this->modelUsergroup->addPermission($this->user->getId(), 'access', 'module/cart/widget');
			$this->modelUsergroup->addPermission($this->user->getId(), 'modify', 'module/cart/widget');
			$this->modelUsergroup->addPermission($this->user->getId(), 'delete', 'module/cart/widget');

			$this->modelUsergroup->addPermission($this->user->getId(), 'create', 'module/cart/plugin');
			$this->modelUsergroup->addPermission($this->user->getId(), 'access', 'module/cart/plugin');
			$this->modelUsergroup->addPermission($this->user->getId(), 'modify', 'module/cart/plugin');
			$this->modelUsergroup->addPermission($this->user->getId(), 'delete', 'module/cart/plugin');

			$this->redirect(Url::createAdminUrl('extension/module'));
		}
	}
	
	/**
	 * ControllerModuleCartInstall::validate()
	 * 
	 * @return
	 */
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/cart/install')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
