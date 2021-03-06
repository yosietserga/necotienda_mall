<?php
/**
 * ControllerModuleBestsellerInstall
 * 
 * @package NecoTienda
 * @author Yosiet Serga
 * @copyright Inversiones Necoyoad, C.A.
 * @version 1.0.0
 * @access public
 * @see Controller
 */
class ControllerModuleBestsellerInstall extends Controller {
	private $error = array(); 
	
	/**
	 * ControllerModuleBestsellerInstall::index()
	 * 
	 * @return
	 */
	public function index() {
		if (!$this->user->hasPermission('modify', 'module/bestseller/install')) {
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
			$this->modelExtension->install('module', 'bestseller');
		
			$this->modelUsergroup->addPermission($this->user->getId(), 'access', 'module/bestseller/install');
			$this->modelUsergroup->addPermission($this->user->getId(), 'modify', 'module/bestseller/install');
            
			$this->modelUsergroup->addPermission($this->user->getId(), 'access', 'module/bestseller/uninstall');
			$this->modelUsergroup->addPermission($this->user->getId(), 'modify', 'module/bestseller/uninstall');

			$this->modelUsergroup->addPermission($this->user->getId(), 'access', 'module/bestseller/widget');
			$this->modelUsergroup->addPermission($this->user->getId(), 'modify', 'module/bestseller/widget');
			$this->modelUsergroup->addPermission($this->user->getId(), 'delete', 'module/bestseller/widget');

			$this->redirect(Url::createAdminUrl('extension/module'));
		}
	}
	
	/**
	 * ControllerModuleBestsellerInstall::validate()
	 * 
	 * @return
	 */
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/bestseller/install')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
