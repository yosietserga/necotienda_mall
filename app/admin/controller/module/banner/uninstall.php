<?php
/**
 * ControllerModuleBannerUninstall
 * 
 * @package NecoTienda
 * @author Yosiet Serga
 * @copyright Inversiones Necoyoad, C.A.
 * @version 1.0.0
 * @access public
 * @see Controller
 */
class ControllerModuleBannerUninstall extends Controller {
	private $error = array(); 
	
	/**
	 * ControllerModuleBannerUninstall::index()
	 * 
	 * @return
	 */
	public function index() {   
		if (!$this->user->hasPermission('modify', 'module/banner/uninstall')) {
			$this->session->set('error',$this->language->get('error_permission')); ; 
			$this->redirect(Url::createAdminUrl('extension/module'));
		} else {
            $this->load->auto('setting/extension');
            $this->load->auto('setting/setting');
            $this->load->auto('style/widget');
			$this->modelExtension->uninstall('module', 'banner');
			$this->modelSetting->delete('banner');
            $this->modelWidget->deleteAll('banner');
			$this->redirect(Url::createAdminUrl('extension/module'));	
		}
	}
}
