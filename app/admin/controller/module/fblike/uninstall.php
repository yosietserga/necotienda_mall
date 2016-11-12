<?php
/**
 * ControllerModuleFBLikeUninstall
 * 
 * @package NecoTienda
 * @author Yosiet Serga
 * @copyright Inversiones Necoyoad, C.A.
 * @version 1.0.0
 * @access public
 * @see Controller
 */
class ControllerModuleFBLikeUninstall extends Controller {
	private $error = array(); 
	
	/**
	 * ControllerModuleFBLikeUninstall::index()
	 * 
	 * @return
	 */
	public function index() {   
		if (!$this->user->hasPermission('modify', 'module/fblike/uninstall')) {
			$this->session->set('error',$this->language->get('error_permission')); ; 
			$this->redirect(Url::createAdminUrl('extension/module'));
		} else {
            $this->load->auto('setting/extension');
            $this->load->auto('setting/setting');
            $this->load->auto('style/widget');
			$this->modelExtension->uninstall('module', 'fblike');
			$this->modelSetting->delete('fblike');
            $this->modelWidget->deleteAll('fblike');
			$this->redirect(Url::createAdminUrl('extension/module'));	
		}
	}
}
