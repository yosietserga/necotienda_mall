<?php
/**
 * ControllerModuleLinksUninstall
 * 
 * @package NecoTienda
 * @author Yosiet Serga
 * @copyright Inversiones Necoyoad, C.A.
 * @version 1.0.0
 * @access public
 * @see Controller
 */
class ControllerModuleLinksUninstall extends Controller {
	private $error = array(); 
	
	/**
	 * ControllerModuleLinksUninstall::index()
	 * 
	 * @return
	 */
	public function index() {   
		if (!$this->user->hasPermission('modify', 'module/links/uninstall')) {
			$this->session->set('error',$this->language->get('error_permission')); ; 
			$this->redirect(Url::createAdminUrl('extension/module'));
		} else {
            $this->load->auto('setting/extension');
            $this->load->auto('setting/setting');
            $this->load->auto('style/widget');
			$this->modelExtension->uninstall('module', 'links');
			$this->modelSetting->delete('links');
            $this->modelWidget->deleteAll('links');
			$this->redirect(Url::createAdminUrl('extension/module'));	
		}
	}
}
