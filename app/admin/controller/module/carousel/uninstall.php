<?php
/**
 * ControllerModuleCarouselUninstall
 * 
 * @package NecoTienda
 * @author Yosiet Serga
 * @copyright Inversiones Necoyoad, C.A.
 * @version 1.0.0
 * @access public
 * @see Controller
 */
class ControllerModuleCarouselUninstall extends Controller {
	private $error = array(); 
	
	/**
	 * ControllerModuleCarouselUninstall::index()
	 * 
	 * @return
	 */
	public function index() {   
		if (!$this->user->hasPermission('modify', 'module/carousel/uninstall')) {
			$this->session->set('error',$this->language->get('error_permission')); ; 
			$this->redirect(Url::createAdminUrl('extension/module'));
		} else {
            $this->load->auto('setting/extension');
            $this->load->auto('setting/setting');
            $this->load->auto('style/widget');
			$this->modelExtension->uninstall('module', 'carousel');
			$this->modelSetting->delete('carousel');
            $this->modelWidget->deleteAll('carousel');
			$this->redirect(Url::createAdminUrl('extension/module'));	
		}
	}
}
