<?php
/**
 * ControllerModuleTwitterUninstall
 * 
 * @package NecoTienda
 * @author Yosiet Serga
 * @copyright Inversiones Necoyoad, C.A.
 * @version 1.0.0
 * @access public
 * @see Controller
 */
class ControllerModuleTwitterUninstall extends Controller {
	private $error = array(); 
	
	/**
	 * ControllerModuleTwitterUninstall::index()
	 * 
	 * @return
	 */
	public function index() {   
		if (!$this->user->hasPermission('modify', 'module/twitter/uninstall')) {
			$this->session->set('error',$this->language->get('error_permission')); ; 
			$this->redirect(Url::createAdminUrl('extension/module'));
		} else {
            $this->load->auto('setting/extension');
            $this->load->auto('setting/setting');
            $this->load->auto('style/widget');
			$this->modelExtension->uninstall('module', 'twitter');
			$this->modelSetting->delete('twitter');
            $this->modelWidget->deleteAll('twitter');
			$this->redirect(Url::createAdminUrl('extension/module'));	
		}
	}
}
