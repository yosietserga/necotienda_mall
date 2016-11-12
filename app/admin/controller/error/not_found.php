<?php    
class ControllerErrorNotFound extends Controller {    
	public function index() { 
    	$this->load->language('error/not_found');
 
    	$this->document->title = $this->language->get('heading_title');

    	$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_not_found'] = $this->language->get('text_not_found');

  		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => Url::createAdminUrl('common/home'),
       		'text'      => $this->language->get('text_home'),
      		'separator' => false
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => Url::createAdminUrl('error/not_found'),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
		
		$this->template = 'error/not_found.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(true), $this->config->get('config_compression'));	
  	}
}
