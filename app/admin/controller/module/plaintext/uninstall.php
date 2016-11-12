<?php
/**
 * ControllerModulePlainTextUninstall
 * 
 * @package NecoTienda
 * @author Yosiet Serga
 * @copyright Inversiones Necoyoad, C.A.
 * @version 1.0.0
 * @access public
 * @see Controller
 */
class ControllerModulePlainTextUninstall extends Controller {
	private $error = array(); 
	
	/**
	 * ControllerModulePlainTextUninstall::index()
	 * 
	 * @return
	 */
	public function index() {   
		if (!$this->user->hasPermission('modify', 'module/plaintext/uninstall')) {
			$this->session->set('error',$this->language->get('error_permission')); ; 
			$this->redirect(Url::createAdminUrl('extension/module'));
		} else {
            $this->load->auto('setting/extension');
            $this->load->auto('setting/setting');
            $this->load->auto('style/widget');
			$this->modelExtension->uninstall('module', 'plaintext');
			$this->modelSetting->delete('plaintext');
            $this->modelWidget->deleteAll('plaintext');
			$this->redirect(Url::createAdminUrl('extension/module'));	
		}
	}
}
