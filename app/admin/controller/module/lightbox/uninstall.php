<?php
/**
 * ControllerModuleLightboxUninstall
 * 
 * @package NecoTienda
 * @author Yosiet Serga
 * @copyright Inversiones Necoyoad, C.A.
 * @version 1.0.0
 * @access public
 * @see Controller
 */
class ControllerModuleLightboxUninstall extends Controller {
	private $error = array(); 
	
	/**
	 * ControllerModuleLightboxUninstall::index()
	 * 
	 * @return
	 */
	public function index() {   
		if (!$this->user->hasPermission('modify', 'module/lightbox/uninstall')) {
			$this->session->set('error',$this->language->get('error_permission')); ; 
			$this->redirect(Url::createAdminUrl('extension/module'));
		} else {
            $this->load->auto('setting/extension');
            $this->load->auto('setting/setting');
            $this->load->auto('style/widget');
			$this->modelExtension->uninstall('module', 'lightbox');
			$this->modelSetting->delete('lightbox');
            $this->modelWidget->deleteAll('lightbox');
			$this->redirect(Url::createAdminUrl('extension/module'));	
		}
	}
}
