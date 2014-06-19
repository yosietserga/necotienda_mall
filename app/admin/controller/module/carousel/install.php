<?php
/**
 * ControllerModuleCarouselInstall
 * 
 * @package NecoTienda
 * @author Yosiet Serga
 * @copyright Inversiones Necoyoad, C.A.
 * @version 1.0.0
 * @access public
 * @see Controller
 */
class ControllerModuleCarouselInstall extends Controller {
	private $error = array(); 
	
	/**
	 * ControllerModuleCarouselInstall::index()
	 * 
	 * @return
	 */
	public function index() {
		if (!$this->user->hasPermission('modify', 'module/carousel/install')) {
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
			$this->modelExtension->install('module', 'carousel');
		
			$this->modelUsergroup->addPermission($this->user->getId(), 'access', 'module/carousel/install');
			$this->modelUsergroup->addPermission($this->user->getId(), 'modify', 'module/carousel/install');
            
			$this->modelUsergroup->addPermission($this->user->getId(), 'access', 'module/carousel/uninstall');
			$this->modelUsergroup->addPermission($this->user->getId(), 'modify', 'module/carousel/uninstall');

			$this->modelUsergroup->addPermission($this->user->getId(), 'access', 'module/carousel/widget');
			$this->modelUsergroup->addPermission($this->user->getId(), 'modify', 'module/carousel/widget');
			$this->modelUsergroup->addPermission($this->user->getId(), 'delete', 'module/carousel/widget');

			$this->redirect(Url::createAdminUrl('extension/module'));
		}
	}
	
	/**
	 * ControllerModuleCarouselInstall::validate()
	 * 
	 * @return
	 */
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/carousel/install')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
