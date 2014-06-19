<?php 
class ControllerSaleContact extends Controller { 
    var $error = array();
    
	public function index() {
		$this->load->model('sale/call');
		$this->load->model('catalog/product');
		$this->load->model('localisation/length_class');
		$this->load->model('account/customer');
        
		$this->data['base']       = HTTP_HOME;
        $this->data['product_id'] = $this->request->getQuery('product_id');
        $this->data['isLogged']   = $this->customer->isLogged();
        $this->data['token']      = (!$this->session->has('token')) ? md5(rand() . time()) : $this->session->get('token');
        $this->data['metrics']    = $this->modelLength_class->getLengthClasses();
   	    
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/sale/contact.tpl')) {
    		$this->template = $this->config->get('config_template') . '/sale/contact.tpl';
    	} else {
            $this->template = 'default/sale/contact.tpl';
    	}
        
		$this->response->setOutput($this->render(true), $this->config->get('config_compression'));		
  	}
}