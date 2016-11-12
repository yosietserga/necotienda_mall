<?php  
class ControllerModuleSeller extends Controller {
	protected function index($widget=null) {
		$this->language->load('module/seller');
        
        if (isset($widget)) {
            $this->data['settings'] = $settings = (array)unserialize($widget['settings']);
            $this->data['widget_hook'] = $this->data['widgetName'] = $widget['name'];
        } else {
            $this->data['widgetName'] = 'seller';
        }
        
		if (isset($settings['title'])) {
            $this->data['heading_title'] = $settings['title'];
		} else {
            $this->data['heading_title'] = $this->language->get('heading_title');
		}
        
        $this->load->model('account/customer');
        
        $seller = $this->modelCustomer->getById($this->request->getQuery('seller_id'));
        if ($seller) {
            $this->data['seller_info'] = $seller_info;
        }
        
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/seller/'. $this->data['seller']['jquery_plugin'] .'.tpl')) {
            $this->template = $this->config->get('config_template') . '/module/seller.tpl';
		} else {
            $this->template = 'default/module/seller.tpl';
		}
            
		$this->id = 'seller';
		$this->render();
	}
    
    public function fullinfo() {
        $this->load->model('account/customer');
        $this->load->model('account/address');
        
        $seller = $this->modelCustomer->getCustomer($this->request->getQuery('seller_id'));
        $seller['address'] = $this->modelAddress->getAddress($seller['address_id']);
        if ($seller) {
            $this->data['seller_info'] = $seller_info;
        }
        
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/module/seller.tpl')) {
            $this->template = $this->config->get('config_template') . '/module/seller.tpl';
		} else {
            $this->template = 'default/module/seller.tpl';
		}
		
        $this->response->setOutput($this->render(true), $this->config->get('config_compression'));
    }
}
