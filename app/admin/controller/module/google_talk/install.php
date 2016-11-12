<?php
/**
 * ControllerModuleGoogleTalkInstall
 * 
 * @package NecoTienda
 * @author Yosiet Serga
 * @copyright Inversiones Necoyoad, C.A.
 * @version 1.0.0
 * @access public
 * @see Controller
 */
class ControllerModuleGoogleTalkInstall extends Controller {
	private $error = array(); 
	
	/**
	 * ControllerModuleGoogleTalkInstall::index()
	 * 
	 * @return
	 */
	public function index() {
		if (!$this->user->hasPermission('modify', 'module/google_talk/install')) {
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
			$this->modelExtension->install('module', 'google_talk');
		
			$this->modelUsergroup->addPermission($this->user->getId(), 'modify', 'module/google_talk/install');
			$this->modelUsergroup->addPermission($this->user->getId(), 'modify', 'module/google_talk/uninstall');

			$this->modelUsergroup->addPermission($this->user->getId(), 'create', 'module/google_talk/widget');
			$this->modelUsergroup->addPermission($this->user->getId(), 'access', 'module/google_talk/widget');
			$this->modelUsergroup->addPermission($this->user->getId(), 'modify', 'module/google_talk/widget');
			$this->modelUsergroup->addPermission($this->user->getId(), 'delete', 'module/google_talk/widget');

			$this->modelUsergroup->addPermission($this->user->getId(), 'create', 'module/google_talk/plugin');
			$this->modelUsergroup->addPermission($this->user->getId(), 'access', 'module/google_talk/plugin');
			$this->modelUsergroup->addPermission($this->user->getId(), 'modify', 'module/google_talk/plugin');
			$this->modelUsergroup->addPermission($this->user->getId(), 'delete', 'module/google_talk/plugin');

			$this->redirect(Url::createAdminUrl('extension/module'));
		}
	}
	
	/**
	 * ControllerModuleGoogleTalkInstall::validate()
	 * 
	 * @return
	 */
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/google_talk/install')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
