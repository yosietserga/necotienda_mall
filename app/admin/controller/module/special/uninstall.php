<?php
/**
 * ControllerModuleSpecialUninstall
 * 
 * @package NecoTienda
 * @author Yosiet Serga
 * @copyright Inversiones Necoyoad, C.A.
 * @version 1.0.0
 * @access public
 * @see Controller
 */
class ControllerModuleSpecialUninstall extends Controller {
	private $error = array(); 
	
	/**
	 * ControllerModuleSpecialUninstall::index()
	 * 
	 * @return
	 */
	public function index() {   
		if (!$this->user->hasPermission('modify', 'module/special/uninstall')) {
			$this->session->set('error',$this->language->get('error_permission')); ; 
			$this->redirect(Url::createAdminUrl('extension/module'));
		} else {
            $this->load->auto('setting/extension');
            $this->load->auto('setting/setting');
            $this->load->auto('style/widget');
			$this->modelExtension->uninstall('module', 'special');
			$this->modelSetting->delete('special');
            $this->modelWidget->deleteAll('special');
			$this->redirect(Url::createAdminUrl('extension/module'));	
		}
	}
}
