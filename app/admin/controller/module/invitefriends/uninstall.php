<?php
/**
 * ControllerModuleInviteFriendsUninstall
 * 
 * @package NecoTienda
 * @author Yosiet Serga
 * @copyright Inversiones Necoyoad, C.A.
 * @version 1.0.0
 * @access public
 * @see Controller
 */
class ControllerModuleInviteFriendsUninstall extends Controller {
	private $error = array(); 
	
	/**
	 * ControllerModuleInviteFriendsUninstall::index()
	 * 
	 * @return
	 */
	public function index() {   
		if (!$this->user->hasPermission('modify', 'module/invitefriends/uninstall')) {
			$this->session->set('error',$this->language->get('error_permission')); ; 
			$this->redirect(Url::createAdminUrl('extension/module'));
		} else {
            $this->load->auto('setting/extension');
            $this->load->auto('setting/setting');
            $this->load->auto('style/widget');
			$this->modelExtension->uninstall('module', 'invitefriends');
			$this->modelSetting->delete('invitefriends');
            $this->modelWidget->deleteAll('invitefriends');
			$this->redirect(Url::createAdminUrl('extension/module'));	
		}
	}
}
