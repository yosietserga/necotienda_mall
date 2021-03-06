<?php
/**
 * ControllerModuleSkypeMeUninstall
 * 
 * @package NecoTienda
 * @author Yosiet Serga
 * @copyright Inversiones Necoyoad, C.A.
 * @version 1.0.0
 * @access public
 * @see Controller
 */
class ControllerModuleSkypeMeUninstall extends Controller {
	private $error = array(); 
	
	/**
	 * ControllerModuleSkypeMeUninstall::index()
	 * 
	 * @return
	 */
	public function index() {   
		if (!$this->user->hasPermission('modify', 'module/skype_me/uninstall')) {
			$this->session->set('error',$this->language->get('error_permission')); ; 
			$this->redirect(Url::createAdminUrl('extension/module'));
		} else {
            $this->load->auto('setting/extension');
            $this->load->auto('setting/setting');
            $this->load->auto('style/widget');
			$this->modelExtension->uninstall('module', 'skype_me');
			$this->modelSetting->delete('skype_me');
            $this->modelWidget->deleteAll('skype_me');
			$this->redirect(Url::createAdminUrl('extension/module'));	
		}
	}
}
