<?php
/**
 * ControllerModuleManufacturerUninstall
 * 
 * @package NecoTienda
 * @author Yosiet Serga
 * @copyright Inversiones Necoyoad, C.A.
 * @version 1.0.0
 * @access public
 * @see Controller
 */
class ControllerModuleManufacturerUninstall extends Controller {
	private $error = array(); 
	
	/**
	 * ControllerModuleManufacturerUninstall::index()
	 * 
	 * @return
	 */
	public function index() {   
		if (!$this->user->hasPermission('modify', 'module/manufacturer/uninstall')) {
			$this->session->set('error',$this->language->get('error_permission')); ; 
			$this->redirect(Url::createAdminUrl('extension/module'));
		} else {
            $this->load->auto('setting/extension');
            $this->load->auto('setting/setting');
            $this->load->auto('style/widget');
			$this->modelExtension->uninstall('module', 'manufacturer');
			$this->modelSetting->delete('manufacturer');
            $this->modelWidget->deleteAll('manufacturer');
			$this->redirect(Url::createAdminUrl('extension/module'));	
		}
	}
}
