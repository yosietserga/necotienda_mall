<?php
/**
 * ControllerModuleCategoryUninstall
 * 
 * @package NecoTienda
 * @author Yosiet Serga
 * @copyright Inversiones Necoyoad, C.A.
 * @version 1.0.0
 * @access public
 * @see Controller
 */
class ControllerModuleCategoryUninstall extends Controller {
	private $error = array(); 
	
	/**
	 * ControllerModuleCategoryUninstall::index()
	 * 
	 * @return
	 */
	public function index() {   
		if (!$this->user->hasPermission('modify', 'module/category/uninstall')) {
			$this->session->set('error',$this->language->get('error_permission')); ; 
			$this->redirect(Url::createAdminUrl('extension/module'));
		} else {
            $this->load->auto('setting/extension');
            $this->load->auto('setting/setting');
            $this->load->auto('style/widget');
			$this->modelExtension->uninstall('module', 'category');
			$this->modelSetting->delete('category');
            $this->modelWidget->deleteAll('category');
			$this->redirect(Url::createAdminUrl('extension/module'));	
		}
	}
}
