<?php
/**
 * ControllerModuleBestsellerUninstall
 * 
 * @package NecoTienda
 * @author Yosiet Serga
 * @copyright Inversiones Necoyoad, C.A.
 * @version 1.0.0
 * @access public
 * @see Controller
 */
class ControllerModuleBestsellerUninstall extends Controller {
	private $error = array(); 
	
	/**
	 * ControllerModuleBestsellerUninstall::index()
	 * 
	 * @return
	 */
	public function index() {   
		if (!$this->user->hasPermission('modify', 'module/bestseller/uninstall')) {
			$this->session->set('error',$this->language->get('error_permission')); ; 
			$this->redirect(Url::createAdminUrl('extension/module'));
		} else {
            $this->load->auto('setting/extension');
            $this->load->auto('setting/setting');
            $this->load->auto('style/widget');
			$this->modelExtension->uninstall('module', 'bestseller');
			$this->modelSetting->delete('bestseller');
            $this->modelWidget->deleteAll('bestseller');
			$this->redirect(Url::createAdminUrl('extension/module'));	
		}
	}
}
