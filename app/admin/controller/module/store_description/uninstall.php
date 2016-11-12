<?php
/**
 * ControllerModuleStoreDescriptionUninstall
 * 
 * @package NecoTienda
 * @author Yosiet Serga
 * @copyright Inversiones Necoyoad, C.A.
 * @version 1.0.0
 * @access public
 * @see Controller
 */
class ControllerModuleStoreDescriptionUninstall extends Controller {
	private $error = array(); 
	
	/**
	 * ControllerModuleStoreDescriptionUninstall::index()
	 * 
	 * @return
	 */
	public function index() {   
		if (!$this->user->hasPermission('modify', 'module/store_description/uninstall')) {
			$this->session->set('error',$this->language->get('error_permission')); ; 
			$this->redirect(Url::createAdminUrl('extension/module'));
		} else {
            $this->load->auto('setting/extension');
            $this->load->auto('setting/setting');
            $this->load->auto('style/widget');
			$this->modelExtension->uninstall('module', 'store_description');
			$this->modelSetting->delete('store_description');
            $this->modelWidget->deleteAll('store_description');
			$this->redirect(Url::createAdminUrl('extension/module'));	
		}
	}
}
