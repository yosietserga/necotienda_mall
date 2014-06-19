<?php
/**
 * ControllerModuleLoginUninstall
 * 
 * @package NecoTienda
 * @author Yosiet Serga
 * @copyright Inversiones Necoyoad, C.A.
 * @version 1.0.0
 * @access public
 * @see Controller
 */
class ControllerModuleLoginUninstall extends Controller {
	private $error = array(); 
	
	/**
	 * ControllerModuleLoginUninstall::index()
	 * 
	 * @return
	 */
	public function index() {   
		if (!$this->user->hasPermission('modify', 'module/login/uninstall')) {
			$this->session->set('error',$this->language->get('error_permission')); ; 
			$this->redirect(Url::createAdminUrl('extension/module'));
		} else {
            $this->load->auto('setting/extension');
            $this->load->auto('setting/setting');
            $this->load->auto('style/widget');
			$this->modelExtension->uninstall('module', 'login');
			$this->modelSetting->delete('login');
            $this->modelWidget->deleteAll('login');
			$this->redirect(Url::createAdminUrl('extension/module'));	
		}
	}
}
