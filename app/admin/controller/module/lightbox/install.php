<?php
/**
 * ControllerModuleLightboxInstall
 * 
 * @package NecoTienda
 * @author Yosiet Serga
 * @copyright Inversiones Necoyoad, C.A.
 * @version 1.0.0
 * @access public
 * @see Controller
 */
class ControllerModuleLightboxInstall extends Controller {
	private $error = array(); 
	
	/**
	 * ControllerModuleLightboxInstall::index()
	 * 
	 * @return
	 */
	public function index() {
		if (!$this->user->hasPermission('modify', 'module/lightbox/install')) {
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
			$this->modelExtension->install('module', 'lightbox');
		
			$this->modelUsergroup->addPermission($this->user->getId(), 'access', 'module/lightbox/install');
			$this->modelUsergroup->addPermission($this->user->getId(), 'modify', 'module/lightbox/install');
            
			$this->modelUsergroup->addPermission($this->user->getId(), 'access', 'module/lightbox/uninstall');
			$this->modelUsergroup->addPermission($this->user->getId(), 'modify', 'module/lightbox/uninstall');

			$this->modelUsergroup->addPermission($this->user->getId(), 'access', 'module/lightbox/widget');
			$this->modelUsergroup->addPermission($this->user->getId(), 'modify', 'module/lightbox/widget');
			$this->modelUsergroup->addPermission($this->user->getId(), 'delete', 'module/lightbox/widget');

			$this->redirect(Url::createAdminUrl('extension/module'));
		}
	}
	
	/**
	 * ControllerModuleLightboxInstall::validate()
	 * 
	 * @return
	 */
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/lightbox/install')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
