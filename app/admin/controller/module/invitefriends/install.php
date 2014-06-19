<?php
/**
 * ControllerModuleInviteFriendsInstall
 * 
 * @package NecoTienda
 * @author Yosiet Serga
 * @copyright Inversiones Necoyoad, C.A.
 * @version 1.0.0
 * @access public
 * @see Controller
 */
class ControllerModuleInviteFriendsInstall extends Controller {
	private $error = array(); 
	
	/**
	 * ControllerModuleInviteFriendsInstall::index()
	 * 
	 * @return
	 */
	public function index() {
		if (!$this->user->hasPermission('modify', 'module/invitefriends/install')) {
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
			$this->modelExtension->install('module', 'invitefriends');
		
			$this->modelUsergroup->addPermission($this->user->getId(), 'modify', 'module/invitefriends/install');
			$this->modelUsergroup->addPermission($this->user->getId(), 'modify', 'module/invitefriends/uninstall');

			$this->modelUsergroup->addPermission($this->user->getId(), 'create', 'module/invitefriends/widget');
			$this->modelUsergroup->addPermission($this->user->getId(), 'access', 'module/invitefriends/widget');
			$this->modelUsergroup->addPermission($this->user->getId(), 'modify', 'module/invitefriends/widget');
			$this->modelUsergroup->addPermission($this->user->getId(), 'delete', 'module/invitefriends/widget');

			$this->modelUsergroup->addPermission($this->user->getId(), 'create', 'module/invitefriends/plugin');
			$this->modelUsergroup->addPermission($this->user->getId(), 'access', 'module/invitefriends/plugin');
			$this->modelUsergroup->addPermission($this->user->getId(), 'modify', 'module/invitefriends/plugin');
			$this->modelUsergroup->addPermission($this->user->getId(), 'delete', 'module/invitefriends/plugin');

			$this->redirect(Url::createAdminUrl('extension/module'));
		}
	}
	
	/**
	 * ControllerModuleInviteFriendsInstall::validate()
	 * 
	 * @return
	 */
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/invitefriends/install')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
