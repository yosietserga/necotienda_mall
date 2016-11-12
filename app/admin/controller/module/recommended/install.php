<?php
/**
 * ControllerModuleRecommendedInstall
 * 
 * @package NecoTienda
 * @author Yosiet Serga
 * @copyright Inversiones Necoyoad, C.A.
 * @version 1.0.0
 * @access public
 * @see Controller
 */
class ControllerModuleRecommendedInstall extends Controller {
	private $error = array(); 
	
	/**
	 * ControllerModuleRecommendedInstall::index()
	 * 
	 * @return
	 */
	public function index() {
		if (!$this->user->hasPermission('modify', 'module/recommended/install')) {
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
			$this->modelExtension->install('module', 'recommended');
		
			$this->modelUsergroup->addPermission($this->user->getId(), 'access', 'module/recommended/install');
			$this->modelUsergroup->addPermission($this->user->getId(), 'modify', 'module/recommended/install');
            
			$this->modelUsergroup->addPermission($this->user->getId(), 'access', 'module/recommended/uninstall');
			$this->modelUsergroup->addPermission($this->user->getId(), 'modify', 'module/recommended/uninstall');

			$this->modelUsergroup->addPermission($this->user->getId(), 'access', 'module/recommended/widget');
			$this->modelUsergroup->addPermission($this->user->getId(), 'modify', 'module/recommended/widget');
			$this->modelUsergroup->addPermission($this->user->getId(), 'delete', 'module/recommended/widget');

			$this->redirect(Url::createAdminUrl('extension/module'));
		}
	}
	
	/**
	 * ControllerModuleRecommendedInstall::validate()
	 * 
	 * @return
	 */
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/recommended/install')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
