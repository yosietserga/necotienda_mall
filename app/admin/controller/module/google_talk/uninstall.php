<?php
/**
 * ControllerModuleGoogleTalkUninstall
 * 
 * @package NecoTienda
 * @author Yosiet Serga
 * @copyright Inversiones Necoyoad, C.A.
 * @version 1.0.0
 * @access public
 * @see Controller
 */
class ControllerModuleGoogleTalkUninstall extends Controller {
	private $error = array(); 
	
	/**
	 * ControllerModuleGoogleTalkUninstall::index()
	 * 
	 * @return
	 */
	public function index() {   
		if (!$this->user->hasPermission('modify', 'module/google_talk/uninstall')) {
			$this->session->set('error',$this->language->get('error_permission')); ; 
			$this->redirect(Url::createAdminUrl('extension/module'));
		} else {
            $this->load->auto('setting/extension');
            $this->load->auto('setting/setting');
            $this->load->auto('style/widget');
			$this->modelExtension->uninstall('module', 'google_talk');
			$this->modelSetting->delete('google_talk');
            $this->modelWidget->deleteAll('google_talk');
			$this->redirect(Url::createAdminUrl('extension/module'));	
		}
	}
}
