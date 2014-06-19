<?php
/**
 * ControllerModuleGoogleAnalyticsUninstall
 * 
 * @package NecoTienda
 * @author Yosiet Serga
 * @copyright Inversiones Necoyoad, C.A.
 * @version 1.0.0
 * @access public
 * @see Controller
 */
class ControllerModuleGoogleAnalyticsUninstall extends Controller {
	private $error = array(); 
	
	/**
	 * ControllerModuleGoogleAnalyticsUninstall::index()
	 * 
	 * @return
	 */
	public function index() {   
		if (!$this->user->hasPermission('modify', 'module/google_analytics/uninstall')) {
			$this->session->set('error',$this->language->get('error_permission')); ; 
			$this->redirect(Url::createAdminUrl('extension/module'));
		} else {
            $this->load->auto('setting/extension');
            $this->load->auto('setting/setting');
            $this->load->auto('style/widget');
			$this->modelExtension->uninstall('module', 'google_analytics');
			$this->modelSetting->delete('google_analytics');
            $this->modelWidget->deleteAll('google_analytics');
			$this->redirect(Url::createAdminUrl('extension/module'));	
		}
	}
}
