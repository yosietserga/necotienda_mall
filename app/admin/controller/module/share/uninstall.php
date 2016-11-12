<?php
/**
 * ControllerModuleShareUninstall
 * 
 * @package NecoTienda
 * @author Yosiet Serga
 * @copyright Inversiones Necoyoad, C.A.
 * @version 1.0.0
 * @access public
 * @see Controller
 */
class ControllerModuleShareUninstall extends Controller {
	private $error = array(); 
	
	/**
	 * ControllerModuleShareUninstall::index()
	 * 
	 * @return
	 */
	public function index() {   
		if (!$this->user->hasPermission('modify', 'module/share/uninstall')) {
			$this->session->set('error',$this->language->get('error_permission')); ; 
			$this->redirect(Url::createAdminUrl('extension/module'));
		} else {
            $this->load->auto('setting/extension');
            $this->load->auto('setting/setting');
            $this->load->auto('style/widget');
			$this->modelExtension->uninstall('module', 'share');
			$this->modelSetting->delete('share');
            $this->modelWidget->deleteAll('share');
			$this->redirect(Url::createAdminUrl('extension/module'));	
		}
	}
}
