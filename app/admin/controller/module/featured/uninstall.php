<?php
/**
 * ControllerModuleFeaturedUninstall
 * 
 * @package NecoTienda
 * @author Yosiet Serga
 * @copyright Inversiones Necoyoad, C.A.
 * @version 1.0.0
 * @access public
 * @see Controller
 */
class ControllerModuleFeaturedUninstall extends Controller {
	private $error = array(); 
	
	/**
	 * ControllerModuleFeaturedUninstall::index()
	 * 
	 * @return
	 */
	public function index() {   
		if (!$this->user->hasPermission('modify', 'module/featured/uninstall')) {
			$this->session->set('error',$this->language->get('error_permission')); ; 
			$this->redirect(Url::createAdminUrl('extension/module'));
		} else {
            $this->load->auto('setting/extension');
            $this->load->auto('setting/setting');
            $this->load->auto('style/widget');
			$this->modelExtension->uninstall('module', 'featured');
			$this->modelSetting->delete('featured');
            $this->modelWidget->deleteAll('featured');
			$this->redirect(Url::createAdminUrl('extension/module'));	
		}
	}
}
