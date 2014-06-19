<?php
/**
 * ControllerModuleLatestUninstall
 * 
 * @package NecoTienda
 * @author Yosiet Serga
 * @copyright Inversiones Necoyoad, C.A.
 * @version 1.0.0
 * @access public
 * @see Controller
 */
class ControllerModuleLatestUninstall extends Controller {
	private $error = array(); 
	
	/**
	 * ControllerModuleLatestUninstall::index()
	 * 
	 * @return
	 */
	public function index() {   
		if (!$this->user->hasPermission('modify', 'module/latest/uninstall')) {
			$this->session->set('error',$this->language->get('error_permission')); ; 
			$this->redirect(Url::createAdminUrl('extension/module'));
		} else {
            $this->load->auto('setting/extension');
            $this->load->auto('setting/setting');
            $this->load->auto('style/widget');
			$this->modelExtension->uninstall('module', 'latest');
			$this->modelSetting->delete('latest');
            $this->modelWidget->deleteAll('latest');
			$this->redirect(Url::createAdminUrl('extension/module'));	
		}
	}
}
