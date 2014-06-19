<?php
/**
 * ControllerModulePageUninstall
 * 
 * @package NecoTienda
 * @author Yosiet Serga
 * @copyright Inversiones Necoyoad, C.A.
 * @version 1.0.0
 * @access public
 * @see Controller
 */
class ControllerModulePageUninstall extends Controller {
	private $error = array(); 
	
	/**
	 * ControllerModulePageUninstall::index()
	 * 
	 * @return
	 */
	public function index() {   
		if (!$this->user->hasPermission('modify', 'module/page/uninstall')) {
			$this->session->set('error',$this->language->get('error_permission')); ; 
			$this->redirect(Url::createAdminUrl('extension/module'));
		} else {
            $this->load->auto('setting/extension');
            $this->load->auto('setting/setting');
            $this->load->auto('style/widget');
			$this->modelExtension->uninstall('module', 'page');
			$this->modelSetting->delete('page');
            $this->modelWidget->deleteAll('page');
			$this->redirect(Url::createAdminUrl('extension/module'));	
		}
	}
}
