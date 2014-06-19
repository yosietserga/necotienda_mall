<?php
/**
 * ControllerModuleStoreTitleUninstall
 * 
 * @package NecoTienda
 * @author Yosiet Serga
 * @copyright Inversiones Necoyoad, C.A.
 * @version 1.0.0
 * @access public
 * @see Controller
 */
class ControllerModuleStoreTitleUninstall extends Controller {
	private $error = array(); 
	
	/**
	 * ControllerModuleStoreTitleUninstall::index()
	 * 
	 * @return
	 */
	public function index() {   
		if (!$this->user->hasPermission('modify', 'module/store_title/uninstall')) {
			$this->session->set('error',$this->language->get('error_permission')); ; 
			$this->redirect(Url::createAdminUrl('extension/module'));
		} else {
            $this->load->auto('setting/extension');
            $this->load->auto('setting/setting');
            $this->load->auto('style/widget');
			$this->modelExtension->uninstall('module', 'store_title');
			$this->modelSetting->delete('store_title');
            $this->modelWidget->deleteAll('store_title');
			$this->redirect(Url::createAdminUrl('extension/module'));	
		}
	}
}
