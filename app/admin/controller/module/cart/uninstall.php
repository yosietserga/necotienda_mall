<?php
/**
 * ControllerModuleCartUninstall
 * 
 * @package NecoTienda
 * @author Yosiet Serga
 * @copyright Inversiones Necoyoad, C.A.
 * @version 1.0.0
 * @access public
 * @see Controller
 */
class ControllerModuleCartUninstall extends Controller {
	private $error = array(); 
	
	/**
	 * ControllerModuleCartUninstall::index()
	 * 
	 * @return
	 */
	public function index() {   
		if (!$this->user->hasPermission('modify', 'module/cart/uninstall')) {
			$this->session->set('error',$this->language->get('error_permission')); ; 
			$this->redirect(Url::createAdminUrl('extension/module'));
		} else {
            $this->load->auto('setting/extension');
            $this->load->auto('setting/setting');
            $this->load->auto('style/widget');
			$this->modelExtension->uninstall('module', 'cart');
			$this->modelSetting->delete('cart');
            $this->modelWidget->deleteAll('cart');
			$this->redirect(Url::createAdminUrl('extension/module'));	
		}
	}
}
