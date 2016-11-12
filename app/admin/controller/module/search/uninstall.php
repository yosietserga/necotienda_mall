<?php
/**
 * ControllerModuleSearchUninstall
 * 
 * @package NecoTienda
 * @author Yosiet Serga
 * @copyright Inversiones Necoyoad, C.A.
 * @version 1.0.0
 * @access public
 * @see Controller
 */
class ControllerModuleSearchUninstall extends Controller {
	private $error = array(); 
	
	/**
	 * ControllerModuleSearchUninstall::index()
	 * 
	 * @return
	 */
	public function index() {   
		if (!$this->user->hasPermission('modify', 'module/search/uninstall')) {
			$this->session->set('error',$this->language->get('error_permission')); ; 
			$this->redirect(Url::createAdminUrl('extension/module'));
		} else {
            $this->load->auto('setting/extension');
            $this->load->auto('setting/setting');
            $this->load->auto('style/widget');
			$this->modelExtension->uninstall('module', 'search');
			$this->modelSetting->delete('search');
            $this->modelWidget->deleteAll('search');
			$this->redirect(Url::createAdminUrl('extension/module'));	
		}
	}
}
