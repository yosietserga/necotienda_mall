<?php
/**
 * ControllerModuleStoreLogoUninstall
 * 
 * @package NecoTienda
 * @author Yosiet Serga
 * @copyright Inversiones Necoyoad, C.A.
 * @version 1.0.0
 * @access public
 * @see Controller
 */
class ControllerModuleStoreLogoUninstall extends Controller {
	private $error = array(); 
	
	/**
	 * ControllerModuleStoreLogoUninstall::index()
	 * 
	 * @return
	 */
	public function index() {   
		if (!$this->user->hasPermission('modify', 'module/store_logo/uninstall')) {
			$this->session->set('error',$this->language->get('error_permission')); ; 
			$this->redirect(Url::createAdminUrl('extension/module'));
		} else {
            $this->load->auto('setting/extension');
            $this->load->auto('setting/setting');
            $this->load->auto('style/widget');
			$this->modelExtension->uninstall('module', 'store_logo');
			$this->modelSetting->delete('store_logo');
            $this->modelWidget->deleteAll('store_logo');
			$this->redirect(Url::createAdminUrl('extension/module'));	
		}
	}
}
