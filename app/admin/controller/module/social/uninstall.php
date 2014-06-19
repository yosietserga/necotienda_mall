<?php
/**
 * ControllerModuleSocialUninstall
 * 
 * @package NecoTienda
 * @author Yosiet Serga
 * @copyright Inversiones Necoyoad, C.A.
 * @version 1.0.0
 * @access public
 * @see Controller
 */
class ControllerModuleSocialUninstall extends Controller {
	private $error = array(); 
	
	/**
	 * ControllerModuleSocialUninstall::index()
	 * 
	 * @return
	 */
	public function index() {   
		if (!$this->user->hasPermission('modify', 'module/social/uninstall')) {
			$this->session->set('error',$this->language->get('error_permission')); ; 
			$this->redirect(Url::createAdminUrl('extension/module'));
		} else {
            $this->load->auto('setting/extension');
            $this->load->auto('setting/setting');
            $this->load->auto('style/widget');
			$this->modelExtension->uninstall('module', 'social');
			$this->modelSetting->delete('social');
            $this->modelWidget->deleteAll('social');
			$this->redirect(Url::createAdminUrl('extension/module'));	
		}
	}
}
