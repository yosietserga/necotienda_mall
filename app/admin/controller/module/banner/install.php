<?php
/**
 * ControllerModuleBannerInstall
 * 
 * @package NecoTienda
 * @author Yosiet Serga
 * @copyright Inversiones Necoyoad, C.A.
 * @version 1.0.0
 * @access public
 * @see Controller
 */
class ControllerModuleBannerInstall extends Controller {
	private $error = array(); 
	
	/**
	 * ControllerModuleBannerInstall::index()
	 * 
	 * @return
	 */
	public function index() {
		if (!$this->user->hasPermission('modify', 'module/banner/install')) {
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
			$this->modelExtension->install('module', 'banner');
		
			$this->modelUsergroup->addPermission($this->user->getId(), 'access', 'module/banner/install');
			$this->modelUsergroup->addPermission($this->user->getId(), 'modify', 'module/banner/install');
            
			$this->modelUsergroup->addPermission($this->user->getId(), 'access', 'module/banner/uninstall');
			$this->modelUsergroup->addPermission($this->user->getId(), 'modify', 'module/banner/uninstall');

			$this->modelUsergroup->addPermission($this->user->getId(), 'access', 'module/banner/widget');
			$this->modelUsergroup->addPermission($this->user->getId(), 'modify', 'module/banner/widget');
			$this->modelUsergroup->addPermission($this->user->getId(), 'delete', 'module/banner/widget');

			$this->redirect(Url::createAdminUrl('extension/module'));
		}
	}
	
	/**
	 * ControllerModuleBannerInstall::validate()
	 * 
	 * @return
	 */
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/banner/install')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
